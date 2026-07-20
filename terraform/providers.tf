terraform {
  required_version = ">= 1.5.0"

  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

# Provider AWS configurado para hablar con Floci (emulador local)

provider "aws" {
  region     = var.aws_region
  access_key = "test"
  secret_key = "test"

  # Evita validar credenciales contra IAM real (que no existe en Floci)
  skip_credentials_validation = true
  skip_metadata_api_check     = true
  skip_requesting_account_id  = true

  # S3 en Floci requiere path-style URLs
  s3_use_path_style = true

  # Redirigimos cada servicio AWS al endpoint de Floci
  endpoints {
    s3             = "http://localhost:4566"
    ec2            = "http://localhost:4566"
    iam            = "http://localhost:4566"
    ecs            = "http://localhost:4566"
    rds            = "http://localhost:4566"
    elbv2          = "http://localhost:4566"
    secretsmanager = "http://localhost:4566"
    logs           = "http://localhost:4566"
    sts            = "http://localhost:4566"
  }
}