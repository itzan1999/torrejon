@extends('pages.Layouts.plantillaDefault')

@section('titulo','Aviso Legal - Leche El Torrejon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/pages/Legal/legal.css') }}">
@endpush

@section('contenido')
    <div class="container py-4">
        <h1 class="mb-4">Aviso legal</h1>
        <p>
            Los derechos de propiedad intelectual de este portal de Internet, su diseño, sus gráficos y los códigos que contiene son titularidad de LECHE EL TORREJÓN S.L.<br>
            La reproducción, distribución, comercialización o trasformación no autorizadas de esta obra, a no ser que sea para uso personal o privado, 
            constituye una infracción de los derechos de propiedad intelectual. Igualmente, todas las marcas o signos distintivos de cualquier clase están protegidos por la Ley.<br>
            La utilización no autorizada de la información contenida en este portal, así como los perjuicios ocasionados en los derechos de la propiedad intelectual e 
            industrial pueden dar lugar al ejercicio de las acciones que legalmente correspondan y, si procede, a las responsabilidades que dichos ejercicios se deriven.<br>
            LECHE EL TORREJÓN S.L. vigilará y procurara con todos su esfuerzo evitar errores y en su caso repararlos o actualizarlos lo antes posible, 
            no pudiendo garantizar su inexistencia ni que el contenido de la información se encuentre permanentemente actualizado.<br>
            LECHE EL TORREJÓN S.L. podrá efectuar en cualquier momento y sin necesidad de previo aviso, modificaciones y 
            actualizaciones sobre la información contenida en su portal o en su configuración o presentación.<br>
            El acceso al portal, así como el uso que pueda hacerse de la información que contiene son de exclusiva responsabilidad de LECHE EL TORREJÓN S.L., 
            la cual no se responsabilizará de ninguna consecuencia daño o perjuicio que pudieran derivarse de este acceso o uso de información.<br>
            La información proporcionada a través del portal tiene carácter meramente orientativo.<br>
            LECHE EL TORREJÓN S.L. no asume ninguna responsabilidad derivada de conexión o contenidos de los enlaces de terceros a los que se pueda hacer referencia en el portal.<br>
            Los datos que se recojan se trasladaran informativamente o se archivaran con el consentimiento del usuario, el cual puede ejercer sus derechos de acceso rectificación y cancelación, 
            a través de los procedimientos oportunos, así como tiene derecho a decidir quién puede tener sus datos, para que los usa, solicitar que los mismos sean exactos y que se utilicen 
            para el fin por el que se recogen.
        </p>
    </div>
@endsection