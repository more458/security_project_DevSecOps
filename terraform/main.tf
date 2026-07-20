# Recurso de prueba: bucket S3 para validar que el toolchain funciona
# Terraform → AWS Provider → Floci → recurso creado
resource "aws_s3_bucket" "test" {
  bucket = "${var.project_name}-${var.environment}-test-bucket"

  tags = {
    Project     = var.project_name
    Environment = var.environment
    ManagedBy   = "Terraform"
    Purpose     = "Toolchain validation - can be deleted"
  }
}