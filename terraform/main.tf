# ============================================================
# VPC — La red virtual aislada donde vivirá toda la infra
# ============================================================
resource "aws_vpc" "main" {
  cidr_block = "10.0.0.0/16"

  # Habilita resolución de nombres DNS dentro de la VPC
  # (necesario para que los recursos se encuentren por hostname)
  enable_dns_support   = true
  enable_dns_hostnames = true

  tags = {
    Name        = "${var.project_name}-vpc"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# SUBREDES PÚBLICAS — Para recursos que miran a internet (ALB)
# ============================================================
resource "aws_subnet" "public_a" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.1.0/24"
  availability_zone = "${var.aws_region}a"

  # Las instancias que se lancen acá reciben IP pública automáticamente
  map_public_ip_on_launch = true   # nosemgrep: aws-subnet-has-public-ip-address -- Subred publica intencional: aloja el ALB que debe ser accesible desde internet. Las cargas sensibles (app, BD) viven en subredes privadas.


  tags = {
    Name        = "${var.project_name}-public-a"
    Tier        = "public"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

resource "aws_subnet" "public_b" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.2.0/24"
  availability_zone = "${var.aws_region}b"

  map_public_ip_on_launch = true   # nosemgrep: aws-subnet-has-public-ip-address -- Subred publica intencional: aloja el ALB que debe ser accesible desde internet. Las cargas sensibles (app, BD) viven en subredes privadas.


  tags = {
    Name        = "${var.project_name}-public-b"
    Tier        = "public"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# SUBREDES PRIVADAS — Para app y BD (sin acceso directo a internet)
# ============================================================
resource "aws_subnet" "private_a" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.11.0/24"
  availability_zone = "${var.aws_region}a"

  # NO asignamos IP pública — este es el punto clave de seguridad
  map_public_ip_on_launch = false

  tags = {
    Name        = "${var.project_name}-private-a"
    Tier        = "private"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

resource "aws_subnet" "private_b" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.12.0/24"
  availability_zone = "${var.aws_region}b"

  map_public_ip_on_launch = false

  tags = {
    Name        = "${var.project_name}-private-b"
    Tier        = "private"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# INTERNET GATEWAY — La puerta de la VPC hacia internet
# ============================================================
resource "aws_internet_gateway" "main" {
  vpc_id = aws_vpc.main.id

  tags = {
    Name        = "${var.project_name}-igw"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# ROUTE TABLE PÚBLICA — Ruta el tráfico de internet vía el IGW
# ============================================================
resource "aws_route_table" "public" {
  vpc_id = aws_vpc.main.id

  # Regla: todo el tráfico hacia internet (0.0.0.0/0)
  # sale por el Internet Gateway
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.main.id
  }

  tags = {
    Name        = "${var.project_name}-public-rt"
    Tier        = "public"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# ROUTE TABLE PRIVADA — SIN ruta a internet (aislamiento)
# ============================================================
resource "aws_route_table" "private" {
  vpc_id = aws_vpc.main.id

  # Intencionalmente NO tiene ruta a 0.0.0.0/0
  # Los recursos aquí solo pueden comunicarse dentro de la VPC
  # (la ruta local 10.0.0.0/16 la agrega AWS automáticamente)

  tags = {
    Name        = "${var.project_name}-private-rt"
    Tier        = "private"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# ASOCIACIONES — Vincular cada subred con su route table
# ============================================================

# Las subredes públicas usan la route table pública (con salida a internet)
resource "aws_route_table_association" "public_a" {
  subnet_id      = aws_subnet.public_a.id
  route_table_id = aws_route_table.public.id
}

resource "aws_route_table_association" "public_b" {
  subnet_id      = aws_subnet.public_b.id
  route_table_id = aws_route_table.public.id
}

# Las subredes privadas usan la route table privada (sin salida a internet)
resource "aws_route_table_association" "private_a" {
  subnet_id      = aws_subnet.private_a.id
  route_table_id = aws_route_table.private.id
}

resource "aws_route_table_association" "private_b" {
  subnet_id      = aws_subnet.private_b.id
  route_table_id = aws_route_table.private.id
}

# ============================================================
# SECURITY GROUP — ALB (Balanceador de carga)
# ============================================================
resource "aws_security_group" "alb" {
  name        = "${var.project_name}-alb-sg"
  description = "Permite trafico HTTP/HTTPS desde internet hacia el ALB"
  vpc_id      = aws_vpc.main.id

  ingress {
    description = "HTTP desde internet"
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    description = "HTTPS desde internet"
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    description = "Permitir salida hacia la app"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name        = "${var.project_name}-alb-sg"
    Tier        = "public"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# SECURITY GROUP — App (Contenedores ECS)
# ============================================================
resource "aws_security_group" "app" {
  name        = "${var.project_name}-app-sg"
  description = "Permite trafico solo desde el ALB hacia los contenedores"
  vpc_id      = aws_vpc.main.id

  ingress {
    description     = "Trafico desde el ALB unicamente"
    from_port       = 8080
    to_port         = 8080
    protocol        = "tcp"
    security_groups = [aws_security_group.alb.id]
  }

  egress {
    description = "Permitir salida hacia BD y servicios AWS"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name        = "${var.project_name}-app-sg"
    Tier        = "private"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# SECURITY GROUP — Base de Datos (RDS MySQL)
# ============================================================
resource "aws_security_group" "db" {
  name        = "${var.project_name}-db-sg"
  description = "Permite trafico MySQL solo desde los contenedores de la app"
  vpc_id      = aws_vpc.main.id

  ingress {
    description     = "MySQL solo desde la app"
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.app.id]
  }

  egress {
    description = "Permitir respuestas hacia la app"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name        = "${var.project_name}-db-sg"
    Tier        = "private"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# DB SUBNET GROUP — Define en qué subredes puede vivir la BD
# ============================================================
resource "aws_db_subnet_group" "main" {
  name       = "${var.project_name}-db-subnet-group"
  subnet_ids = [aws_subnet.private_a.id, aws_subnet.private_b.id]

  tags = {
    Name        = "${var.project_name}-db-subnet-group"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# RDS MYSQL — Base de datos gestionada
# ============================================================
resource "aws_db_instance" "main" {
  identifier     = "${var.project_name}-db"
  engine         = "mysql"
  engine_version = "8.0"
  instance_class = "db.t3.micro" # Elegible para Free Tier en AWS real

  # Almacenamiento
  allocated_storage     = 20   # GB (mínimo de Free Tier)
  max_allocated_storage = 100  # Permite autoscaling de storage hasta 100 GB
  storage_type          = "gp3"
  storage_encrypted     = true # Cifrado en reposo — buena práctica de seguridad

  # Credenciales (vienen de variables, no hardcodeadas)
  db_name  = var.db_name
  username = var.db_username
  password = var.db_password

  # Red — vive en las subredes privadas, con el SG de BD
  db_subnet_group_name   = aws_db_subnet_group.main.name
  vpc_security_group_ids = [aws_security_group.db.id]
  publicly_accessible    = false # NUNCA accesible desde internet

  # Backups y mantenimiento
  backup_retention_period = 7           # Retener backups 7 días
  skip_final_snapshot     = true        # Para dev; en prod sería false
  deletion_protection     = false       # Para dev; en prod sería true

  # Alta disponibilidad (lo dejamos en false para Floci/dev)
  multi_az = false

  # Habilitar exportación de logs a CloudWatch (seguridad y auditoría)
  enabled_cloudwatch_logs_exports = ["error", "general", "slowquery"]

  tags = {
    Name        = "${var.project_name}-db"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}