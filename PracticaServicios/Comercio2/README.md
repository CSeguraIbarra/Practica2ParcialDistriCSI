Comercio2 - Cliente consola mínimo (C# .NET 6)

Requisitos:
- Tener instalado .NET 6 SDK
- Intermediador corriendo en http://127.0.0.1:8000

Usar:
```bash
cd Comercio2
dotnet run --project Comercio2.csproj
```

El cliente pedirá email/password y luego datos de la transacción. Valores por defecto: `test@example.com` / `secret`, cuentaOrigen `1001`, monto `10`, cuentaDestino `2002`.
