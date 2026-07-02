# ZZTheme — Tema WooCommerce

Tema WordPress/WooCommerce personalizado para **ZZ Contigo Maturín**, construido con [Milligram CSS](https://milligram.io/) como base y JavaScript puro (sin jQuery propio).

---

## Requisitos

| Requisito | Versión mínima |
|-----------|---------------|
| WordPress | 6.0+ |
| WooCommerce | 8.0+ |
| PHP | 7.4+ |

---

## Instalación

1. Descarga o clona este repositorio dentro de la carpeta de temas de WordPress:
   ```
   wp-content/themes/zztheme/
   ```
2. En el panel de WordPress ve a **Apariencia → Temas** y activa **ZZTheme**.
3. Ve a **WooCommerce → Ajustes** y verifica que WooCommerce esté correctamente configurado.
4. Configura el tema en **Apariencia → Personalizar**.

---

## Características

- **Sin jQuery propio** — JavaScript puro en `assets/js/main.js`
- **Dark mode** — Toggle con persistencia en `localStorage`
- **Carrito AJAX** — Contador del header actualizado sin recargar página
- **Toast de confirmación** — Aviso visual al agregar productos al carrito
- **Wishlist** — Lista de deseos con localStorage
- **Dropdown de categorías** — Menú desplegable en el header con subcategorías
- **Carruseles** — Secciones de "Destacados", "Recién Llegados" y "Ofertas"
- **Modo oscuro** — Compatible con `prefers-color-scheme`
- **Responsive** — Mobile-first, menú hamburger
- **My Account estilizada** — Panel de usuario, sidebar de navegación con fondo oscuro en ítem activo

---

## Opciones del Personalizador

Accede desde **Apariencia → Personalizar**:

### Panel: Inicio
| Sección | Controles |
|---------|-----------|
| **Hero** | Título, subtítulo, texto CTA, URL CTA, imagen de fondo |
| **Destacados** | Título de la sección, cantidad de productos (4–24) |
| **Recién Llegados** | Título, cantidad de productos, días de antigüedad (0 = sin límite) |
| **Ofertas** | Título de la sección, cantidad de productos |
| **Categorías** | Título + 3 secciones de categoría por ID |
| **Newsletter** | Activar/desactivar, título, subtítulo |
| **Banners** | 2 banners con imagen, título, texto y URL |

### Configuración global
| Sección | Controles |
|---------|-----------|
| **Colores** | Color primario, color oscuro |
| **Contacto** | Teléfono, email, dirección, WhatsApp |
| **Redes Sociales** | Facebook, Instagram, WhatsApp, Twitter/X |
| **Página Contacto** | URL embed de Google Maps |

---

## Estructura de Archivos

```
zztheme/
├── style.css                        # Cabecera del tema (sin CSS real)
├── functions.php                    # Carga de inc/
├── header.php                       # Topbar + Logo + Buscador + Nav
├── footer.php                       # Footer + scripts
├── front-page.php                   # Homepage (6 secciones)
├── archive-product.php              # Tienda: sidebar + grid
├── single-product.php               # Producto individual
├── page.php / sidebar.php           # Páginas genéricas
├── page-quienes-somos.php           # Template: Quiénes Somos
├── page-contacto.php                # Template: Contacto con formulario AJAX
├── page-wishlist.php                # Template: Lista de Deseos
│
├── inc/
│   ├── setup.php                    # Soporte del tema, menús, sidebars
│   ├── enqueue.php                  # Carga de CSS/JS
│   ├── customizer.php               # Todas las opciones del personalizador
│   ├── woocommerce.php              # Hooks y filtros de WooCommerce
│   ├── ajax.php                     # AJAX: formulario de contacto
│   ├── menu-walker.php              # Walker para submenús
│   ├── template-tags.php            # Helpers: branding, breadcrumb, contacto
│   └── update-check.php            # ⚠️ Aviso automático si hay templates desactualizados
│
├── assets/
│   ├── css/
│   │   ├── milligram.min.css        # Milligram 1.4.1 (base)
│   │   ├── base.css                 # Variables CSS, reset, tipografía
│   │   ├── layout.css               # Container, header, footer, grid
│   │   ├── components.css           # Tarjetas, botones, nav, toasts
│   │   ├── woocommerce.css          # Overrides específicos de WooCommerce
│   │   └── dark-mode.css            # Modo oscuro
│   └── js/
│       └── main.js                  # JavaScript sin dependencias
│
├── template-parts/
│   ├── header/topbar.php            # Barra superior de contacto
│   ├── header/mini-cart.php         # Ícono del carrito
│   ├── home/hero.php                # Banner principal
│   ├── home/features.php            # 3 características
│   ├── home/featured-products.php   # Carrusel productos destacados
│   ├── home/new-products.php        # Carrusel recién llegados
│   ├── home/sale-products.php       # Carrusel ofertas
│   ├── home/banners.php             # Banners promocionales
│   ├── home/categories.php          # Secciones por categoría
│   └── content/                     # Contenido de páginas genéricas
│
└── woocommerce/                     # ⚠️ Overrides de templates WooCommerce
    ├── content-product.php          # Tarjeta de producto en loops
    ├── content-single-product.php   # Producto individual
    └── myaccount/
        └── navigation.php          # ⚠️ Nav de Mi Cuenta (ver sección abajo)
```

---

## ⚠️ Templates de WooCommerce Sobreescritos

El tema modifica **3 templates nativos de WooCommerce**. Cuando WooCommerce se actualiza y cambia alguno de estos archivos, el tema puede quedar desactualizado.

### Detección automática

El tema incluye un sistema de aviso automático (`inc/update-check.php`). Si hay templates desactualizados, verás un **aviso amarillo en el panel de WordPress** (visible solo para administradores) con el nombre del archivo y las versiones afectadas.

### Templates sobreescritos y qué se modificó

#### 1. `woocommerce/myaccount/navigation.php`
**Versión base:** WooCommerce 9.3.0

Se añadió el **panel de usuario** (avatar + nombre) dentro del `<nav>`:

```php
<?php if ( is_user_logged_in() ) : ?>
<div class="account-nav-user">
    <div class="account-nav-user__avatar">
        <!-- avatar o SVG por defecto -->
    </div>
    <div class="account-nav-user__info">
        <span class="account-nav-user__label">Bienvenido,</span>
        <strong class="account-nav-user__name"><?php echo esc_html( $name ); ?></strong>
    </div>
</div>
<?php endif; ?>
```

#### 2. `woocommerce/content-product.php`
**Versión base:** WooCommerce 9.x

Tarjeta de producto personalizada con la estructura `.product-card` del tema.

#### 3. `woocommerce/content-single-product.php`
**Versión base:** WooCommerce 9.x

Layout de producto individual con `.sp__gallery` + `.sp__summary`.

---

### Cómo actualizar un template desactualizado

Cuando WooCommerce se actualiza y aparece el aviso:

**Paso 1** — Ve a **WooCommerce → Estado del sistema** y busca la sección **"Overrides de plantillas"**. Verás el archivo marcado en rojo con un enlace **"Ver diferencia"**.

**Paso 2** — Haz clic en **"Ver diferencia"**. Verás exactamente qué cambió en la nueva versión de WooCommerce (líneas en rojo = eliminadas, verdes = nuevas).

**Paso 3** — Abre el archivo correspondiente en la carpeta del tema:
```
wp-content/themes/zztheme/woocommerce/myaccount/navigation.php
```

**Paso 4** — Aplica los cambios de WooCommerce **conservando** las modificaciones del tema:

Para `navigation.php`, asegúrate de que el bloque `<div class="account-nav-user">...</div>` siga estando dentro del `<nav>`, justo antes del `<ul>`.

**Paso 5** — Actualiza la línea `@version` en el comentario de cabecera del archivo del tema para que coincida con la versión nueva de WooCommerce. Esto eliminará el aviso.

```php
 * @version 9.4.0   ← actualizar este número
```

> **Tip:** Si no te sientes cómodo haciendo este cambio, avísale a tu desarrollador. El aviso en el panel describe exactamente qué archivo revisar.

---

## Variables CSS Principales

```css
:root {
  --zz-primary:       #3f82ef;   /* Azul principal */
  --zz-primary-dark:  #2563d4;   /* Azul oscuro (hover) */
  --zz-dark:          #1a2c4a;   /* Texto oscuro / fondo nav activo */
  --zz-text:          #3d4554;   /* Texto general */
  --zz-bg:            #ffffff;   /* Fondo */
  --zz-surface:       #f5f5f5;   /* Superficies */
  --zz-border:        #e0e0e0;   /* Bordes */
  --zz-success:       #22c55e;   /* Verde éxito */
  --zz-danger:        #ef4444;   /* Rojo error/oferta */
}
```

Puedes cambiar `--zz-primary` y `--zz-dark` desde **Apariencia → Personalizar → Colores** sin tocar código.

---

## Licencia

GPLv2 o posterior — igual que WordPress.
