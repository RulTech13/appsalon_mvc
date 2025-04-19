let paso=1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora : '',
    servicios: []
};

//Cuandop esté cargada la página, ejecuta la function.
document.addEventListener('DOMContentLoaded' , function() {

    iniciasApp() ;
});
//----------------------------------------------------------------------------------------------

function iniciasApp() {

    tabs();//cambia los tabs
    botonesPaginador();//control de los botones de paginador
    paginaSiguiente();
    paginaAnterior();

    
    consultarAPI() ;//Consultar la API de servicios en el backend de php
    
    idCliente();//Carga el id del cliente al objeto cita
    nombreCliente();//Añade nombre de cliente al objeto cita
    seleccionarFecha();//Añade la fecha al objeto cita
    seleccionarHora();//Añade la hora al objeto cita
    mostrarResumen();//Visualizar la cita completa en la pestaña resumen
}//---------------------------------------------------------------------------------------------

function mostrarSeccion() {

    //Ocultar la seccion mostrada anteriormente
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
        seccionAnterior.classList.add('ocultar');
    } 
      
    //Seleccionar la seccion dependiendo del paso (tab) clicado
    // etiqueta html del tab es data-paso=1 donde data- indica que le sigue una etiqueta creada por nosotros. paso es nombre de la etiqueta. =1 valor que le damos a la etiqueta
    const pasoSelector = `#paso-${paso}`; 
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');
    seccion.classList.remove('ocultar');

    //Quitar la clase actual al tab activo anterior
    const tabAnt = document.querySelector('.actual');
    if(tabAnt) {
    tabAnt.classList.remove('actual');
    }
    //Resaltar tab clicado
    //seleccionar el tab por atributo =  `[data-paso]` , corchetes y nombre del atributo del elemento en el index.php. fiajrse en tipo de comillas...
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

    //actualizar paginador
    botonesPaginador();
    
}//---------------------------------------------------------------------------------------------

function tabs() {

    const botones = document.querySelectorAll('.tabs button');
    
    //botones es nodeList(3)
    botones.forEach( boton => {

        //recorremos uno a uno añadiendo listener click pasandole el evento e
        boton.addEventListener('click' , function(e) {
            //guarda en la variable paso el valor de la etiqueta data-paso del tab. puede ser valor 1,2,3
                paso = parseInt( e.target.dataset.paso );
                mostrarSeccion();
        });
    });
}//---------------------------------------------------------------------------------------------

function botonesPaginador() {

    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    //controlar que en el primer tab no actua el paginador anterior
    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
    } else {
        paginaAnterior.classList.remove('ocultar');
    }

    //controlar que en el ultimo tab no actua el paginador siguiente
    if(paso === 3) {
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        paginaSiguiente.classList.remove('ocultar');
    }
}//---------------------------------------------------------------------------------------------

function paginaAnterior() {
    const pagAnt = document.querySelector('#anterior');//id del boton anterior
    pagAnt.addEventListener('click' , function(){

        if(paso <= pasoInicial) return;
        paso--;
        mostrarSeccion();
    });
}//---------------------------------------------------------------------------------------------

function paginaSiguiente() {

    const pagSig = document.querySelector('#siguiente');//id del boton siguiente
    pagSig.addEventListener('click' , function(){

        if(paso >= pasoFinal) return;
        paso++;
        mostrarSeccion();
    });
}//---------------------------------------------------------------------------------------------

async function consultarAPI() {

    try {
        //Template string que toma el punto inicial del proyecto. location.origin es global
        const url = `${location.origin}/api/servicios`;
        //await espera el fin de la consulta a la api hecha con el fetch a la url indicada
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }

}//---------------------------------------------------------------------------------------------

function mostrarServicios(servicios) {

    servicios.forEach( servicio => {
        //destructuring-> crea tres variables a partir del arreglo servicio
        const { id , nombre , precio } = servicio;

        const nombreServicio =  document.createElement('P');//creo un parrafo para el servicio
        nombreServicio.classList.add('nombre-servicio');//añadimos clase css
        nombreServicio.textContent = nombre;

        const precioServicio =  document.createElement('P');//creo un parrafo para el servicio
        precioServicio.classList.add('precio-servicio');//añadimos clase css
        precioServicio.textContent = `$${precio}`//aparece el precio como: $400

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id; //al div le creamos un atributo personalizado: data- llamado idServicio con el valor de la variable id
        //En el .onclik debemos llamar la funcion con un callback si no solo se ejecuta una sola vez.
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        };

        //añadir al div los parrafos de nombre y precio
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);//añadir al contenedor div #servicios de la pagina /cita, el div creado aqui
    })

}//---------------------------------------------------------------------------------------------

function seleccionarServicio(servicio) {
    
    //seleccion del id sel servicio pasado por parametro en la funcion .onclick
    const {id} = servicio;

    //cita creado al inicio: const cita = {nombre: '',fecha: '',hora : '',servicios: []};
    const {servicios} = cita;
    //cada div de servicio tiene un atributo personalizado llamado data-id-servicio=
    //identificamos el elemento clicado
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); //ojo con el tipo de comillas!!


    //detectar si el servicio estaba seleccionado para seleccionarlo o deseleccionarlo
    if(servicios.some(agregado => agregado.id === id)) {

        //YA ESTABA AGREGADO, eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');

    } else {

        //AGREGARLO
        //en servicios estan guardados todos los servicios[] creados en cita
        //en cita.servicios se guardan todos los servicios que hay, si es que habia clicados, y se añade el servicio clicado
        // ... es el spread operator
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');

    }
}//---------------------------------------------------------------------------------------------

function idCliente() {
    cita.id = document.querySelector('#id').value;
}//---------------------------------------------------------------------------------------------

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}//---------------------------------------------------------------------------------------------

function seleccionarFecha() {

    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function(e) {
        //e.target.value es la fecha seleccionada y lanzada por el evento e. 
        // La creamos con new porque nos proporciona mas datos, asi podemos saber el dia de la semana que es
        //.getUTCDay() proporciona el numero de la semana, 0 domingo, 1 lunes 2...
        const dia = new Date(e.target.value).getUTCDay();

        if( [6 ,0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Sábados y Domingos CERRADO', 'error', '#paso-2 p');
        } else {
            cita.fecha = e.target.value;
        }
    });

}//---------------------------------------------------------------------------------------------

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    //Evitar la acomulacion de alertas 
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    //Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    //Eliminar la alerta
    if(desaparece) {
        setTimeout( () => {
            alerta.remove();
        }, 3000);
    }
}//---------------------------------------------------------------------------------------------

function seleccionarHora() {

    const inputHora = document.querySelector('#hora');

    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        //crea una cadena con los valores separados segun el caracter indicado por parametro
        //[0] indica que ya nos quedamos con el primer elemento de la cadena generada
        const hora = horaCita.split(":")[0];
        
        if(hora < 10 || hora > 18 || hora == 13 || hora == 14) {
            mostrarAlerta('Hora no disponibles' , 'error', '#paso-2 p');
            e.target.value = '';
        } else {
            cita.hora = e.target.value;
        }
    });
}//---------------------------------------------------------------------------------------------

function mostrarResumen() {

    let mensaje = '';

    const resumen = document.querySelector('.contenido-resumen');

    while(resumen.firstChild) { resumen.removeChild(resumen.firstChild);}

    //Object.values(cita) muestra los valores del objeto
    //.includes('') si hay algun valor vacio retorna true
    if(Object.values(cita).includes('') ) {mensaje += 'Faltan datos. '}
    if (cita.servicios.length === 0) {mensaje += 'Faltan Servicios.';}
    if (mensaje !== '') {
        mostrarAlerta(mensaje , 'error', '.contenido-resumen', false);
        mensaje = '';
        return;
    }

    //Formatear el div de resumen
    const {nombre, fecha, hora, servicios} = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //Formatear la fecha
    //el mes de enero es mes 0
    //domingo es el dia 0 de la semana
    //no modifica la cita.fecha ya que la debemos guardar en la bd con el formato original

    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate();
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC( year, mes , dia));

    const opciones = {weekday: 'long' , year: 'numeric' , month: 'long' , day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-ES' , opciones);

    const fechaCliente = document.createElement('P');
    fechaCliente.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;

    const horaCliente = document.createElement('P');
    horaCliente.innerHTML = `<span>Hora: </span>${hora} horas`;

    //Heading para el cliente
    const headingCliente = document.createElement('H3');
    headingCliente.textContent =  'Datos del Cliente';
    resumen.appendChild(headingCliente);

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    //Heading para el resumen de servicios
    const headingServicios = document.createElement('H3');
    headingServicios.textContent =  'Resumen de Servicios';
    resumen.appendChild(headingServicios);

        servicios.forEach(servicio => {
            const { id, precio, nombre} = servicio;

            const contenedorServicio = document.createElement('DIV');
            contenedorServicio.classList.add('contenedor-servicio');
            
            const textoServicio = document.createElement('P');
            textoServicio.textContent = nombre;

            const precioServicio = document.createElement('P');
            precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;

            contenedorServicio.appendChild(textoServicio);
            contenedorServicio.appendChild(precioServicio);

            resumen.appendChild(contenedorServicio);
        });

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = '✔ Reservar Cita';
    //la funcion sin parentesis cuando instanciamos el evento .onclick
    //para ponerle parentesis y parametros utilizar el callback,  = function() { reservarCita(id) };
    botonReservar.onclick = reservarCita; 
    resumen.appendChild(botonReservar);

}//---------------------------------------------------------------------------------------------

async function reservarCita() {

    const {nombre , fecha, hora, id, servicios} = cita;

    //map itera sobre servicios pero solo guarda en idServicios el id que coincide con los id guardados en servicio=>servicio.id
    const idServicios = servicios.map( servicio => servicio.id);

    //Formulario de datos en JS, actua como submit
    const datos = new FormData();
    
    datos.append('fecha' , fecha);
    datos.append('hora' , hora);
    datos.append('usuarioId' , id);
    datos.append('servicios' , idServicios);
    // console.log([...datos]); --> Spread operator para poder separarlos

    try {
            //Peticion hacia la api
            const url = `${location.origin}/api/citas`;

            const respuesta = await fetch( url , {
                method: 'POST',
                body: datos //le indicamos al fetch que el formData se encuentra en la variable datos
            });

            const resultado = await respuesta.json();

            if(resultado.resultado) {
                //instrucciones obtenidas de la web sweetalert2
                //script instalado en views/cita/index.php
                Swal.fire({
                    icon: "success",
                    title: "Cita creada",
                    text: "Tú Cita ha sido registrada correctamente.",
                    button: 'Ok'
                }).then ( () => {
                    window.location.reload();
                })
            } 
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "No ha sido Posible Guardar la Cita",
            button: 'Entendido'
          });
    }
    
}