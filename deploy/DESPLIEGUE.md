# Publicar el portafolio gratis en Oracle Cloud

Costo: **$0**. Oracle Cloud "Always Free" da un servidor gratis para siempre y
DuckDNS un subdominio gratis (`tunombre.duckdns.org`) con HTTPS.

Tiempo aproximado: 30–45 minutos.

---

## 1. Crear la cuenta de Oracle Cloud

1. Entra en <https://www.oracle.com/cloud/free/> → **Start for free**.
2. Rellena tus datos. Te pedirán una **tarjeta solo para verificar** tu identidad;
   no te cobran mientras uses recursos marcados como *Always Free*.
3. **Home Region**: elige la más cercana (p. ej. *Brazil East (São Paulo)* o la que
   tengas en Latinoamérica). **No se puede cambiar después.**

## 2. Crear el servidor (instancia)

1. Menú ☰ → **Compute → Instances → Create instance**.
2. **Name**: `portafolio`.
3. **Image**: *Change image* → **Ubuntu** → **Canonical Ubuntu 24.04**.
4. **Shape**: *Change shape* → **Ampere** → `VM.Standard.A1.Flex` con **1 OCPU y 6 GB**
   (marcado *Always Free-eligible*).
   - Si dice que no hay capacidad, prueba más tarde u otro *Availability domain*,
     o usa **Specialty and previous generation → `VM.Standard.E2.1.Micro`** (también gratis).
5. **Networking**: deja que cree la red (VCN) nueva y marca **Assign a public IPv4 address**.
6. **Add SSH keys** → **Generate a key pair for me** → **Save private key**.
   Guarda el archivo `.key` en, por ejemplo, `C:\Users\User\.ssh\oracle-portafolio.key`.
7. **Create**. Cuando el estado sea *Running*, copia la **Public IP address**.

## 3. Abrir los puertos web (80 y 443)

1. En la página de la instancia → **Primary VNIC → Subnet** → **Security Lists** →
   *Default Security List*.
2. **Add Ingress Rules** (dos reglas):

   | Source CIDR | IP Protocol | Destination Port |
   |---|---|---|
   | `0.0.0.0/0` | TCP | `80` |
   | `0.0.0.0/0` | TCP | `443` |

   (El firewall interno del servidor lo abre el script automáticamente.)

## 4. Subdominio gratis con DuckDNS (para tener HTTPS)

1. Entra en <https://www.duckdns.org> con tu cuenta de Google o GitHub.
2. Escribe un nombre, p. ej. `victorcabria` → **add domain**.
3. En **current ip** pega la IP pública de Oracle → **update ip**.

Tu dirección será `victorcabria.duckdns.org`. *(Si más adelante compras un dominio
propio, solo lo apuntas a la misma IP y vuelves a ejecutar el script con él.)*

## 5. Exportar tus datos locales

En **PowerShell** de tu PC, con Laragon encendido:

```powershell
cd C:\laragon\www\portafolio
& "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe" -u root --no-tablespaces --result-file=portafolio.sql portafolio
```

> Usa `--result-file` y no `>`: en PowerShell `>` guarda el archivo en otra
> codificación y la importación falla.

Si subiste imágenes desde el panel, están en `storage\app\public`; ver el paso 8.

## 6. Conectarte al servidor y subir los datos

En PowerShell (cambia `IP` por tu IP pública):

```powershell
# Protege la clave (SSH la rechaza si otros usuarios pueden leerla)
icacls "$env:USERPROFILE\.ssh\oracle-portafolio.key" /inheritance:r /grant:r "$($env:USERNAME):R"

# Sube la copia de la base de datos
scp -i "$env:USERPROFILE\.ssh\oracle-portafolio.key" portafolio.sql ubuntu@IP:~/

# Entra al servidor
ssh -i "$env:USERPROFILE\.ssh\oracle-portafolio.key" ubuntu@IP
```

## 7. Instalar todo con un comando

Ya dentro del servidor:

```bash
curl -fsSL https://raw.githubusercontent.com/VictorCabria/portafolio/main/deploy/setup.sh -o setup.sh
bash setup.sh victorcabria.duckdns.org
```

El script instala PHP 8.3, MySQL, Nginx, Composer y Node; crea la base de datos con
una contraseña aleatoria; importa tu `portafolio.sql`; compila los estilos; configura
Nginx y activa HTTPS. Si no importaste datos, te pedirá crear el usuario administrador.

Al terminar verás: **¡Listo! Tu portafolio está en https://victorcabria.duckdns.org**

Después de la importación, tu login es el mismo que en local.
**Cambia la contraseña temporal** en cuanto entres.

## 8. (Opcional) Subir imágenes que tengas en local

```powershell
scp -i "$env:USERPROFILE\.ssh\oracle-portafolio.key" -r storage\app\public\* ubuntu@IP:/var/www/portafolio/storage/app/public/
```

## Publicar cambios futuros

1. En tu PC: haz los cambios, `git commit` y `git push`.
2. En el servidor:

   ```bash
   bash /var/www/portafolio/deploy/actualizar.sh
   ```

Tus datos (perfil, proyectos, experiencia, imágenes) **no se tocan** al actualizar:
viven en la base de datos y en `storage/` del servidor, no en GitHub.

---

## Problemas frecuentes

| Síntoma | Solución |
|---|---|
| La página no carga en el navegador | Revisa las reglas del paso 3 y que DuckDNS tenga la IP correcta. |
| `certbot` falla | DuckDNS aún no apunta a la IP (espera unos minutos) o falta el puerto 80 en el paso 3. Vuelve a ejecutar `bash setup.sh tu-dominio`. |
| `Permission denied (publickey)` | Usuario incorrecto (es `ubuntu`) o falta el `icacls` del paso 6. |
| Error 500 | En el servidor: `tail -n 50 /var/www/portafolio/storage/logs/laravel.log`. |
| "Out of capacity" al crear la instancia | Reintenta más tarde o usa `VM.Standard.E2.1.Micro`. |
