variable "aws_region" {
  description = "Región de AWS donde desplegar la infraestructura"
  type        = string
  default     = "us-east-1"
}

variable "project_name" {
  description = "Nombre del proyecto, usado como prefijo en los recursos"
  type        = string
  default     = "sweetvibes"
}

variable "environment" {
  description = "Entorno de despliegue (dev, staging, prod)"
  type        = string
  default     = "dev"
}

variable "db_name" {
  description = "Nombre de la base de datos"
  type        = string
  default     = "mi_ecomerce"
}

variable "db_username" {
  description = "Usuario administrador de la base de datos"
  type        = string
  default     = "ecommerce_app"
}

variable "db_password" {
  description = "Contraseña de la base de datos (se pasa por variable, nunca hardcodeada)"
  type        = string
  sensitive   = true
}

variable "use_kms_encryption" {
  description = "Habilita cifrado KMS en logs. Falso para Floci (no lo soporta), true para AWS real."
  type        = bool
  default     = false
}

variable "enable_high_availability" {
  description = "Habilita features de HA (Multi-AZ). Falso en Floci, true en AWS real."
  type        = bool
  default     = false
}