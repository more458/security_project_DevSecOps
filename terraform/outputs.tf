output "vpc_id" {
  description = "ID de la VPC creada"
  value       = aws_vpc.main.id
}

output "vpc_cidr" {
  description = "Rango CIDR de la VPC"
  value       = aws_vpc.main.cidr_block
}

output "public_subnet_ids" {
  description = "IDs de las subredes públicas"
  value       = [aws_subnet.public_a.id, aws_subnet.public_b.id]
}

output "private_subnet_ids" {
  description = "IDs de las subredes privadas"
  value       = [aws_subnet.private_a.id, aws_subnet.private_b.id]
}

output "alb_security_group_id" {
  description = "ID del security group del ALB"
  value       = aws_security_group.alb.id
}

output "app_security_group_id" {
  description = "ID del security group de la app"
  value       = aws_security_group.app.id
}

output "db_security_group_id" {
  description = "ID del security group de la base de datos"
  value       = aws_security_group.db.id
}

output "db_endpoint" {
  description = "Endpoint de conexión de la base de datos"
  value       = aws_db_instance.main.endpoint
}

output "db_name" {
  description = "Nombre de la base de datos"
  value       = aws_db_instance.main.db_name
}