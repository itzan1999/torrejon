<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewController
{
    /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA RUTAS DE PÁGINAS PRINCIPALES
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('pages.Auth.login');
    }

    // Función que devuelve la vista del index
    public function index()
    {
        return view('pages.index');
    }

    // Función que devuelve la vista del el-torrejon
    public function elTorrejon()
    {
        return view('pages.eltorrejon');
    }

    // Función que devuelve la vista de calidad
    public function calidad()
    {
        return view('pages.calidad');
    }

    // Función que devuelve la vista de tienda
    public function tienda()
    {
        return view('pages.tienda');
    }

    // Función que devuelva la vista de noticias
    public function noticias()
    {
        return view('pages.noticias');
    }

    // Functión que devuelta la vista de noticia1
    public function recetaChocolate()
    {
        return view('pages.Noticias.recetaChocolate');
    }

    // Functión que devuelta la vista de noticia2
    public function aumentoConsumoLeche()
    {
        return view('pages.Noticias.aumentoConsumoLeche');
    }

    // Functión que devuelta la vista de contacto
    public function contacto()
    {
        return view('pages.contacto');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA RUTAS DE PÁGINAS LEGALES
    |--------------------------------------------------------------------------
    */
    // Función que devuelve la vista de aviso-legal
    public function avisoLegal()
    {
        return view('pages.Legal.avisoLegal');
    }

    // Función que devuelve la vista de politica-de-privacidad
    public function politicaDePrivacidad()
    {
        return view('pages.Legal.politicaPrivacidad');
    }

    // Función que devuelve la vista de politica-de-cookies
    public function politicaDeCookies()
    {
        return view('pages.Legal.politicaCookies');
    }

    // Función que devuelve la vista de politica-proteccion-de-datos-personales
    public function politicaDeProteccionDeDatosPersonales()
    {
        return view('pages.Legal.politicaProteccionDeDatosPersonales');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCIONES PARA RUTAS DEL CARRITO Y RELACIONADO
    |--------------------------------------------------------------------------
    */
    // Función para mostrar el carrito del usuario (es decir, la cesta para ya poder realizar el pedido)
    public function verCarrito()
    {
        return view('pages.Carrito.verCarrito');
    }
}
