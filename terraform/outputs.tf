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

output "ecr_repository_url" {
  description = "URL del repositorio ECR"
  value       = aws_ecr_repository.app.repository_url
}

output "ecs_cluster_name" {
  description = "Nombre del cluster ECS"
  value       = aws_ecs_cluster.main.name
}

output "task_definition_arn" {
  description = "ARN de la task definition"
  value       = aws_ecs_task_definition.app.arn
}

output "log_group_name" {
  description = "Nombre del log group de CloudWatch"
  value       = aws_cloudwatch_log_group.app.name
}

output "alb_dns_name" {
  description = "DNS del balanceador (punto de entrada de la app)"
  value       = aws_lb.main.dns_name
}

output "target_group_arn" {
  description = "ARN del target group"
  value       = aws_lb_target_group.app.arn
}