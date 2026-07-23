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
# nosemgrep: aws-subnet-has-public-ip-address -- Subred publica intencional: aloja el ALB que debe ser accesible desde internet. Las cargas sensibles (app, BD) viven en subredes privadas.
resource "aws_subnet" "public_a" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.1.0/24"
  availability_zone = "${var.aws_region}a"

  # Las instancias que se lancen acá reciben IP pública automáticamente
  map_public_ip_on_launch = true


  tags = {
    Name        = "${var.project_name}-public-a"
    Tier        = "public"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# nosemgrep: aws-subnet-has-public-ip-address -- Subred publica intencional: aloja el ALB que debe ser accesible desde internet. Las cargas sensibles (app, BD) viven en subredes privadas.
resource "aws_subnet" "public_b" {
  vpc_id            = aws_vpc.main.id
  cidr_block        = "10.0.2.0/24"
  availability_zone = "${var.aws_region}b"

  map_public_ip_on_launch = true   


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

# ============================================================
# ECR — Registro privado de imágenes Docker
# ============================================================
resource "aws_ecr_repository" "app" {
  name                 = "${var.project_name}-app"
  image_tag_mutability = "IMMUTABLE" # Las tags no se pueden sobrescribir (seguridad)

  # Escanea la imagen en busca de vulnerabilidades al subirla
  image_scanning_configuration {
    scan_on_push = true
  }

  # Cifrado de las imágenes en reposo
  encryption_configuration {
    encryption_type = "AES256"
  }

  tags = {
    Name        = "${var.project_name}-app"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# IAM — Execution Role (lo usa ECS para arrancar el contenedor)
# ============================================================

# Documento de política que define QUIÉN puede asumir este rol
data "aws_iam_policy_document" "ecs_assume_role" {
  statement {
    actions = ["sts:AssumeRole"]
    principals {
      type        = "Service"
      identifiers = ["ecs-tasks.amazonaws.com"]
    }
  }
}

resource "aws_iam_role" "ecs_execution_role" {
  name               = "${var.project_name}-ecs-execution-role"
  assume_role_policy = data.aws_iam_policy_document.ecs_assume_role.json

  tags = {
    Name        = "${var.project_name}-ecs-execution-role"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# Adjuntamos la política gestionada de AWS para execution role
# (permite pull de ECR + escribir logs en CloudWatch)
resource "aws_iam_role_policy_attachment" "ecs_execution_role_policy" {
  role       = aws_iam_role.ecs_execution_role.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy"
}

# ============================================================
# IAM — Task Role (lo usa tu app para hablar con servicios AWS)
# ============================================================
resource "aws_iam_role" "ecs_task_role" {
  name               = "${var.project_name}-ecs-task-role"
  assume_role_policy = data.aws_iam_policy_document.ecs_assume_role.json

  tags = {
    Name        = "${var.project_name}-ecs-task-role"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# Por ahora el task role no tiene políticas adjuntas.
# En el sub-bloque 2d le daremos permiso para leer el secreto
# de la base de datos desde Secrets Manager (least-privilege).

# ============================================================
# ECS CLUSTER — El agrupamiento donde corren los contenedores
# ============================================================
resource "aws_ecs_cluster" "main" {
  name = "${var.project_name}-cluster"

  # Habilita Container Insights (monitoreo y métricas)
  setting {
    name  = "containerInsights"
    value = "enabled"
  }

  tags = {
    Name        = "${var.project_name}-cluster"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# CLOUDWATCH LOGS — Destino centralizado de logs del contenedor
# ============================================================
resource "aws_cloudwatch_log_group" "app" {
  name              = "/ecs/${var.project_name}-app"
  retention_in_days = 30 # Retención explícita: ni infinita ni indefinida

  tags = {
    Name        = "${var.project_name}-app-logs"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# ECS TASK DEFINITION — La "receta" de cómo correr el contenedor
# ============================================================
resource "aws_ecs_task_definition" "app" {
  family                   = "${var.project_name}-app"
  requires_compatibilities = ["FARGATE"]
  network_mode             = "awsvpc" # Obligatorio en Fargate: IP propia por task
  cpu                      = "256"    # 0.25 vCPU
  memory                   = "512"    # 512 MB

  execution_role_arn = aws_iam_role.ecs_execution_role.arn
  task_role_arn      = aws_iam_role.ecs_task_role.arn

  container_definitions = jsonencode([
    {
      name      = "${var.project_name}-app"
      image     = "${aws_ecr_repository.app.repository_url}:latest"
      essential = true

      portMappings = [
        {
          containerPort = 8080
          protocol      = "tcp"
        }
      ]

      # Variables de configuración NO sensibles
      environment = [
        { name = "CI_ENVIRONMENT", value = var.environment },
        { name = "database.default.hostname", value = split(":", aws_db_instance.main.endpoint)[0] },
        { name = "database.default.port", value = "3306" },
        { name = "database.default.DBDriver", value = "MySQLi" },
        { name = "database.default.database", value = var.db_name },
        { name = "database.default.username", value = var.db_username }
      ]

      # NOTA: la contraseña NO va acá. En el sub-bloque 2d la inyectamos
      # desde AWS Secrets Manager usando el bloque `secrets`.

      logConfiguration = {
        logDriver = "awslogs"
        options = {
          "awslogs-group"         = aws_cloudwatch_log_group.app.name
          "awslogs-region"        = var.aws_region
          "awslogs-stream-prefix" = "ecs"
        }
      }

      # Seguridad: sistema de archivos raíz de solo lectura
      readonlyRootFilesystem = false # CodeIgniter necesita escribir en writable/

      # El contenedor ya corre como appuser (UID 1001) por el Dockerfile
      user = "1001"
    }
  ])

  tags = {
    Name        = "${var.project_name}-app-task"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# APPLICATION LOAD BALANCER — Punto de entrada público
# ============================================================
resource "aws_lb" "main" {
  name               = "${var.project_name}-alb"
  internal           = false # Público (mira a internet)
  load_balancer_type = "application"
  security_groups    = [aws_security_group.alb.id]
  subnets            = [aws_subnet.public_a.id, aws_subnet.public_b.id]

  # Seguridad: elimina headers HTTP inválidos (mitiga request smuggling)
  drop_invalid_header_fields = true

  # Buena práctica: evita borrado accidental en producción
  enable_deletion_protection = false # false para dev

  tags = {
    Name        = "${var.project_name}-alb"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# TARGET GROUP — Grupo de destinos (los contenedores) + health check
# ============================================================
resource "aws_lb_target_group" "app" {
  name        = "${var.project_name}-tg"
  port        = 8080
  protocol    = "HTTP"
  vpc_id      = aws_vpc.main.id
  target_type = "ip" # Fargate usa IPs, no instancias EC2

  # Health check: cómo el ALB sabe si el contenedor está sano
  health_check {
    enabled             = true
    path                = "/"
    protocol            = "HTTP"
    matcher             = "200"
    interval            = 30
    timeout             = 5
    healthy_threshold   = 2
    unhealthy_threshold = 3
  }

  tags = {
    Name        = "${var.project_name}-tg"
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
  }
}

# ============================================================
# LISTENER — Escucha en el puerto 80 y enruta al target group
# ============================================================
resource "aws_lb_listener" "http" {
  load_balancer_arn = aws_lb.main.arn
  port              = 80
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.app.arn
  }
}