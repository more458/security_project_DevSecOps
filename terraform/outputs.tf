output "test_bucket_name" {
  description = "Nombre del bucket S3 de prueba"
  value       = aws_s3_bucket.test.bucket
}