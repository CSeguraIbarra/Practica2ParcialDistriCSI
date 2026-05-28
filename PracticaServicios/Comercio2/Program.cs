using System;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Text.Json;
using System.Threading.Tasks;

Console.WriteLine("Comercio2 - Cliente consola (mínimo)");

var api = "http://127.0.0.1:8000";
using var http = new HttpClient();

async Task<string?> Login(string email, string password)
{
    var payload = JsonSerializer.Serialize(new { email, password });
    var r = await http.PostAsync(api + "/api/login", new StringContent(payload, Encoding.UTF8, "application/json"));
    var txt = await r.Content.ReadAsStringAsync();
    if (!r.IsSuccessStatusCode) { Console.WriteLine(txt); return null; }
    using var doc = JsonDocument.Parse(txt);
    if (doc.RootElement.TryGetProperty("token", out var t)) return t.GetString();
    if (doc.RootElement.TryGetProperty("access_token", out var t2)) return t2.GetString();
    return null;
}

async Task SendTransaccion(string token, string cuentaOrigen, double monto, string cuentaDestino)
{
    http.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", token);
    var payload = JsonSerializer.Serialize(new { cuentaOrigen, monto, cuentaDestino, fecha = DateTime.UtcNow.ToString("yyyy-MM-dd") });
    var r = await http.PostAsync(api + "/api/transaccion", new StringContent(payload, Encoding.UTF8, "application/json"));
    var txt = await r.Content.ReadAsStringAsync();
    Console.WriteLine($"HTTP {r.StatusCode}\n{txt}");
}

Console.Write("Email (test@example.com): ");
var email = Console.ReadLine();
if (string.IsNullOrWhiteSpace(email)) email = "test@example.com";
Console.Write("Password (secret): ");
var pwd = Console.ReadLine();
if (string.IsNullOrWhiteSpace(pwd)) pwd = "secret";

var token = await Login(email!, pwd!);
if (token == null) { Console.WriteLine("Login falló"); return; }
Console.WriteLine("Token obtenido.");

Console.Write("Cuenta Origen (1001): "); var co = Console.ReadLine(); if (string.IsNullOrWhiteSpace(co)) co = "1001";
Console.Write("Monto (10): "); var m = Console.ReadLine(); if (!double.TryParse(m, out var monto)) monto = 10.0;
Console.Write("Cuenta Destino (2002): "); var cd = Console.ReadLine(); if (string.IsNullOrWhiteSpace(cd)) cd = "2002";

await SendTransaccion(token, co!, monto, cd!);
Console.WriteLine("Fin.");
