# 🥖 TuPedido

**Sistema Integral de Gestión para Panadería con Asistente Inteligente**

TuPedido es una plataforma web desarrollada para centralizar la gestión operativa de una panadería, integrando en un único sistema los procesos de **ventas, POS, producción, recetas, pedidos y administración**.

El proyecto incorpora además un **asistente inteligente basado en LLM**, capaz de interpretar consultas realizadas en lenguaje natural y utilizar herramientas conectadas a la aplicación para obtener información directamente desde los datos registrados en el sistema.

De esta manera, el usuario puede interactuar con la información del negocio mediante lenguaje natural, sin necesidad de navegar manualmente por los distintos módulos.

---

## 🤖 Asistente Inteligente con LLM

Uno de los componentes diferenciales de TuPedido es su **asistente inteligente**, integrado con un modelo de lenguaje mediante **Groq**.

El asistente no se limita a generar respuestas a partir de información estática. El modelo puede **interpretar la intención de la consulta y determinar qué herramienta necesita utilizar para obtener la información solicitada**.

Las herramientas disponibles están conectadas con funciones específicas de la aplicación que permiten consultar los datos registrados en el sistema.

### 🔄 Flujo de interacción

```text
┌──────────────────────────────┐
│            Usuario           │
│  "¿Cuánto vendimos hoy?"     │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│          LLM / IA            │
│ Interpreta la intención      │
│ de la consulta               │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│       Selección de Tool      │
│  Determina qué herramienta   │
│  necesita utilizar           │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│       Tool / Función         │
│ Encapsula la lógica necesaria│
│ para obtener los datos       │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│       Base de datos          │
│     Información real         │
│       del sistema            │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│          LLM / IA            │
│ Interpreta el resultado y    │
│ genera la respuesta final    │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│            Usuario           │
│ "Hoy se vendieron $..."      │
└──────────────────────────────┘
```

Este enfoque permite utilizar la IA como una **capa inteligente de interacción con el sistema**.

El modelo se encarga de comprender qué necesita el usuario y seleccionar la herramienta adecuada, mientras que la aplicación mantiene el control sobre las operaciones que pueden ejecutarse y sobre la información que puede ser consultada.

Por ejemplo, ante una consulta como:

> **"¿Cuánto vendimos hoy?"**

el modelo interpreta la intención, selecciona la herramienta correspondiente, la aplicación obtiene la información necesaria y el resultado vuelve al modelo para generar una respuesta comprensible para el usuario.

Esto permite consultar información real y actualizada del negocio mediante **lenguaje natural**.

---

## 🎯 Objetivo

El objetivo de TuPedido es proporcionar una solución integral que permita administrar los principales procesos de una panadería desde una única plataforma.

El sistema busca centralizar la información necesaria para gestionar:

* Ventas.
* Pedidos.
* Productos.
* Producción.
* Recetas.
* Inventario y administración operativa.
* Métricas del negocio.

Además, la incorporación del asistente inteligente agrega una nueva forma de interactuar con estos datos, complementando las interfaces tradicionales de administración.

---

## ⚙️ Funcionalidades principales

### 🛒 Punto de venta (POS)

Sistema orientado a la gestión de ventas y operaciones comerciales del negocio.

### 📦 Gestión de pedidos

Administración y seguimiento de pedidos dentro del sistema.

### 🥐 Producción

Gestión de los procesos de producción y elaboración de productos.

### 📋 Recetas

Gestión de recetas y sus componentes, permitiendo relacionar productos elaborados con los insumos necesarios para su producción.

### 📊 Dashboard

Dashboard con **métricas clave del negocio**, proporcionando una visión general de la actividad y facilitando el seguimiento de indicadores relacionados con ventas, producción y productos.

### 🤖 Asistente inteligente

Asistente conversacional integrado con un **LLM mediante Groq**, capaz de interpretar consultas y utilizar herramientas de la aplicación para obtener información registrada en el sistema.

---

## 🏗️ Arquitectura

El proyecto utiliza el patrón **MVC (Model-View-Controller) proporcionado por Laravel** como base arquitectónica.

Sobre esta estructura se incorporaron las capas de **Services** y **Repositories** con el objetivo de lograr una mayor separación de responsabilidades y evitar concentrar la lógica de negocio y el acceso a datos dentro de los controladores.

La arquitectura se organiza conceptualmente de la siguiente manera:

```text
┌─────────────────────────┐
│          View           │
│    Interfaz de usuario  │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│       Controller        │
│ Entrada y coordinación  │
│       de la petición    │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│        Service          │
│      Lógica de negocio  │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│       Repository        │
│   Acceso y consultas    │
│       de datos          │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│          Model          │
│   Entidades / ORM       │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│        MySQL            │
└─────────────────────────┘
```

### Controller

Los **Controllers** se encargan principalmente de recibir las peticiones, validar o coordinar la información necesaria y delegar las operaciones correspondientes.

### Service

Los **Services** concentran la **lógica de negocio** de la aplicación.

Esta capa permite mantener los controladores más simples y reutilizar reglas de negocio desde diferentes puntos de la aplicación.

### Repository

Los **Repositories** encapsulan el acceso y las consultas relacionadas con los datos.

Esta separación permite desacoplar parte de la lógica de negocio de la implementación concreta utilizada para obtener y manipular la información.

### Model

Los **Models** representan las principales entidades del dominio y utilizan el ORM de Laravel para interactuar con la base de datos.

### ¿Por qué esta arquitectura?

La incorporación de Services y Repositories sobre el MVC tradicional de Laravel busca mejorar la **separación de responsabilidades, mantenibilidad y escalabilidad** del sistema.

A medida que una aplicación incorpora módulos y reglas de negocio más complejas, mantener toda la lógica directamente en los Controllers puede hacer que estos crezcan y sean difíciles de mantener.

La utilización de estas capas permite distribuir las responsabilidades de forma más clara y facilita la evolución de la aplicación.

---

## 🧠 Arquitectura de la integración con IA

La integración con Inteligencia Artificial se incorpora como una capa adicional sobre la aplicación.

El LLM **no accede directamente a la base de datos**. En su lugar, interactúa con un conjunto de **tools** previamente definidas en la aplicación.

Cada tool representa una capacidad concreta que la IA puede utilizar. Estas herramientas encapsulan la lógica necesaria para obtener información y devuelven los resultados al modelo.

```text
                    ┌─────────────────┐
                    │     Usuario     │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │       LLM       │
                    │  Interpreta la  │
                    │     consulta    │
                    └────────┬────────┘
                             │
                       Tool Selection
                             │
                             ▼
                    ┌─────────────────┐
                    │      Tool       │
                    │   Capacidad     │
                    │    específica   │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │    Service      │
                    │  Lógica negocio │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │   Repository    │
                    │  Acceso a datos │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │     MySQL       │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │       LLM       │
                    │ Genera respuesta│
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │     Usuario     │
                    └─────────────────┘
```

Este diseño permite integrar la IA con la arquitectura existente sin otorgarle acceso directo a la base de datos y manteniendo las reglas de negocio dentro de la aplicación.

---

## 🧩 Stack tecnológico

| Tecnología       | Uso                                                    |
| ---------------- | ------------------------------------------------------ |
| **PHP**          | Lenguaje principal                                     |
| **Laravel**      | Framework y arquitectura de aplicación                 |
| **MVC**          | Patrón arquitectónico base                             |
| **Services**     | Encapsulación de lógica de negocio                     |
| **Repositories** | Abstracción del acceso a datos                         |
| **Tailwind CSS** | Diseño e interfaz                                      |
| **MySQL**        | Base de datos relacional                               |
| **Groq**         | Inferencia del modelo de lenguaje                      |
| **LLM**          | Interpretación de consultas y generación de respuestas |

---

## 🗄️ Base de datos

La aplicación utiliza **MySQL** como sistema de persistencia.

La base de datos centraliza la información relacionada con las diferentes áreas del negocio, permitiendo establecer relaciones entre productos, recetas, pedidos, ventas, producción y demás entidades de la aplicación.

El acceso a los datos se realiza mediante la lógica de aplicación, utilizando la separación entre **Services, Repositories y Models**.

---

## 📊 Gestión y visualización de información

La plataforma centraliza la información de las diferentes áreas del negocio y proporciona dashboards orientados a facilitar la interpretación de los datos.

El sistema busca convertir los datos operativos en información útil para el seguimiento diario del negocio y la toma de decisiones.

El asistente inteligente complementa estos dashboards permitiendo acceder a determinados datos mediante consultas en lenguaje natural.

---

## 🚀 Enfoque del proyecto

TuPedido fue desarrollado como una solución integral para cubrir diferentes procesos de una panadería desde una única plataforma.

El proyecto combina:

**Gestión empresarial + POS + producción + ventas + análisis de datos + Inteligencia Artificial**

La incorporación del asistente basado en LLM agrega una interfaz conversacional sobre la información existente, permitiendo consultar determinados datos del negocio mediante lenguaje natural.

---

## 📌 Estado

**Proyecto desarrollado como sistema integral de gestión para una panadería.**

La arquitectura fue planteada con una visión de crecimiento, permitiendo incorporar nuevas funcionalidades, automatizaciones y capacidades de Inteligencia Artificial a medida que evolucionen las necesidades del negocio.

---

## 👨‍💻 Autor

**Juan Manuel Aguirre**

Analista de Sistemas · Full Stack Developer
