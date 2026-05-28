using System;
using System.Collections.Generic;
using System.Web.Services;

namespace ServicioEconomico
{
    [WebService(Namespace = "http://tempuri.org/")]
    [WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
    [System.ComponentModel.ToolboxItem(false)]
    public class WebService1 : System.Web.Services.WebService
    {
        // In-memory data (simplest option for exam/demo)
        private static readonly Dictionary<string, CuentaBancaria> Cuentas = new Dictionary<string, CuentaBancaria>(StringComparer.OrdinalIgnoreCase)
        {
            ["1001"] = new CuentaBancaria
            {
                Cuenta = "1001",
                Saldo = 1520.75,
                Movimientos = new List<Movimiento>
                {
                    new Movimiento { Fecha = new DateTime(2026,5,25), Monto = 250.00 },
                    new Movimiento { Fecha = new DateTime(2026,5,26), Monto = -80.25 },
                    new Movimiento { Fecha = new DateTime(2026,5,28), Monto = 150.00 }
                }
            },
            ["2002"] = new CuentaBancaria
            {
                Cuenta = "2002",
                Saldo = 845.10,
                Movimientos = new List<Movimiento>
                {
                    new Movimiento { Fecha = new DateTime(2026,5,24), Monto = 500.00 },
                    new Movimiento { Fecha = new DateTime(2026,5,27), Monto = -154.90 }
                }
            }
        };

        [WebMethod]
        public double consultarSaldo(string cuenta)
        {
            if (string.IsNullOrWhiteSpace(cuenta) || !Cuentas.TryGetValue(cuenta.Trim(), out var c))
                throw new ArgumentException("Cuenta no encontrada");
            return c.Saldo;
        }

        [WebMethod]
        public Movimiento[] historial(string cuenta)
        {
            if (string.IsNullOrWhiteSpace(cuenta) || !Cuentas.TryGetValue(cuenta.Trim(), out var c))
                throw new ArgumentException("Cuenta no encontrada");
            c.Movimientos.Sort((a, b) => DateTime.Compare(a.Fecha, b.Fecha));
            return c.Movimientos.ToArray();
        }
    }

    public class Movimiento
    {
        public DateTime Fecha { get; set; }
        public double Monto { get; set; }
    }

    public class CuentaBancaria
    {
        public string Cuenta { get; set; }
        public double Saldo { get; set; }
        public List<Movimiento> Movimientos { get; set; }
    }
}