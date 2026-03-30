<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController
{
    /*
    |--------------------------------------------------------------------------
    | FUNCIONES DE RUTAS DE LAS PÁGINAS DEL DASHBOARD (ADMIN Y USUARIOS)
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | -------------------------INICIO ADMINNISTRADOR--------------------------
    |--------------------------------------------------------------------------
    */
    // Panel admin

    public function panelAdmin()
    {
        return view('pages.Dashboard.Admin.panelAdmin');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTACTOS
    |--------------------------------------------------------------------------
    */
    public function contactos()
    {
        return view('pages.Dashboard.Admin.Contactos.consultas');
    }

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */

    // Función para devolver la vista de los usuarios
    public function listaUsuariosAdmin()
    {
        return view('pages.Dashboard.Admin.Usuarios.usuarios');
    }
    // Función que devuelve la vista para el detalle de la vista
    public function detalleUsuarioAdmin()
    {
        return view('pages.Dashboard.Admin.Usuarios.detalleUsuario');
    }

    // Función que devuelve la vista para crear un nuevo usuario
    public function nuevoUsuarioAdmin()
    {
        return view('pages.Dashboard.Admin.Usuarios.nuevoUsuario');
    }

    // Función que devuelve la vista para editar el usuario
    public function editarUsuarioAdmin()
    {
        return view('pages.Dashboard.Admin.Usuarios.editarUsuario');
    }

    /*
    |--------------------------------------------------------------------------
    | PEDIDOS
    |--------------------------------------------------------------------------
    */

    // Función que devuelve la vista listar los pedidos
    public function listaPedidosAdmin()
    {
        return view("pages.Dashboard.Admin.Pedidos.pedidos");
    }

    // Función que devuelve la vista para listar un pedido
    public function detallePedidoAdmin()
    {
        return view('pages.Dashboard.Admin.Pedidos.detallePedido');
    }

    // Función que devuelve la vista para editar un pedido
    public function editarPedidoAdmin()
    {
        return view('pages.Dashboard.Admin.Pedidos.editarPedido');
    }

    /*
    |--------------------------------------------------------------------------
    | PEDIDOS
    |--------------------------------------------------------------------------
    */

    // Función que devuelve la vista de listar productos
    public function listaProductosAdmin()
    {
        return view("pages.Dashboard.Admin.Productos.productos");
    }

    // Función que devuelve la vista para crear un nuevo producto
    public function nuevoProductoAdmin()
    {
        return view("pages.Dashboard.Admin.Productos.nuevoProducto");
    }

    // Función que devuelve la vista para listar un producto
    public function detalleProductoAdmin()
    {
        return view("pages.Dashboard.Admin.Productos.detalleProducto");
    }

    // Función que devuelve la vista para editar un producto
    public function editarProductoAdmin()
    {
        return view("pages.Dashboard.Admin.Productos.editarProducto");
    }

    /*
    |--------------------------------------------------------------------------
    | PEDIDOS
    |--------------------------------------------------------------------------
    */

    // Función que devuelve la vista de listar suscripciones
    public function listaSuscripcionesAdmin()
    {
        return view("pages.Dashboard.Admin.Suscripciones.suscripciones");
    }

    /*
    |--------------------------------------------------------------------------
    | ----------------------------FIN ADMINNISTRADOR--------------------------
    |--------------------------------------------------------------------------
    */

    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/

    /*
    |--------------------------------------------------------------------------
    | ----------------------------INICIO USUARIOS-----------------------------
    |--------------------------------------------------------------------------
    */


    // Función para devolver el Panel Usuario
    public function panelUsuario()
    {
        $nombreUsuario = auth()->user()->nombre . " " . auth()->user()->apellidos;
        return view('pages.Dashboard.Usuarios.panelUsuario', compact('nombreUsuario'));
    }

    // Función para mostrar la vista del perfil
    public function perfilUsuario()
    {
        $nombreUsuario = auth()->user()->nombre . " " . auth()->user()->apellidos;
        return view('pages.Dashboard.Usuarios.Perfil.perfil', compact('nombreUsuario'));
    }
}
