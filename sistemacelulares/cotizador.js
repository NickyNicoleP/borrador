// Script para el sistema de cotización de planes de telefonía móvil
// Con conexión a base de datos

$(document).ready(function() {
    let currentStep = 1;
    let selectedService = '';
    let datos = 10;
    let minutos = 1000;
    let sms = 500;
    let email = '';
    let telefono = '';
    
    // Inicializar el cotizador
    initCotizador();
    
    function initCotizador() {
        // Mostrar solo el primer paso
        $('.cotizador-step').removeClass('active');
        $('#step1').addClass('active');
        
        // Configurar eventos para las tarjetas de servicio
        $('.service-card').on('click', function() {
            $('.service-card').removeClass('selected');
            $(this).addClass('selected');
            selectedService = $(this).data('service');
            
            // Habilitar el botón siguiente si se seleccionó un servicio
            $('#btnNext').prop('disabled', false);
        });
        
        // Configurar eventos para los sliders
        $('#datos').on('input', function() {
            datos = parseInt($(this).val());
            $('#datosValue').text(datos + ' GB');
        });
        
        $('#minutos').on('input', function() {
            minutos = parseInt($(this).val());
            $('#minutosValue').text(minutos + ' min');
        });
        
        $('#sms').on('input', function() {
            sms = parseInt($(this).val());
            $('#smsValue').text(sms + ' SMS');
        });
        
        // Configurar botones de navegación
        $('#btnNext').on('click', nextStep);
        $('#btnPrev').on('click', prevStep);
        $('#btnFinalizar').on('click', finalizarCotizacion);
        
        // Configurar formulario de newsletter
        $('#newsletterForm').on('submit', function(e) {
            e.preventDefault();
            const email = $(this).find('input[type="email"]').val();
            suscribirNewsletter(email);
        });
    }
    
    function nextStep() {
        if (currentStep === 1 && !selectedService) {
            alert('Por favor, selecciona un tipo de servicio.');
            return;
        }
        
        if (currentStep < 3) {
            // Ocultar paso actual
            $('#step' + currentStep).removeClass('active');
            
            // Avanzar al siguiente paso
            currentStep++;
            $('#step' + currentStep).addClass('active');
            
            // Actualizar botones de navegación
            updateNavigationButtons();
            
            // Si llegamos al paso 3, calcular y mostrar la cotización
            if (currentStep === 3) {
                calcularCotizacion();
            }
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            // Ocultar paso actual
            $('#step' + currentStep).removeClass('active');
            
            // Retroceder al paso anterior
            currentStep--;
            $('#step' + currentStep).addClass('active');
            
            // Actualizar botones de navegación
            updateNavigationButtons();
        }
    }
    
    function updateNavigationButtons() {
        if (currentStep === 1) {
            $('#btnPrev').hide();
            $('#btnNext').show();
            $('#btnFinalizar').hide();
        } else if (currentStep === 2) {
            $('#btnPrev').show();
            $('#btnNext').show();
            $('#btnFinalizar').hide();
        } else if (currentStep === 3) {
            $('#btnPrev').show();
            $('#btnNext').hide();
            $('#btnFinalizar').show();
        }
    }
    
    function calcularCotizacion() {
        // Enviar datos al servidor para calcular la cotización
        const datosCotizacion = {
            tipo_servicio: selectedService,
            datos: datos,
            minutos: minutos,
            sms: sms
        };
        
        $.ajax({
            url: 'cotizar.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosCotizacion),
            success: function(response) {
                if (response.success) {
                    // Actualizar la interfaz con los resultados
                    $('#precioFinal').text('$' + response.precio_final);
                    $('#resDatos').text(response.caracteristicas.datos + ' GB');
                    $('#resMinutos').text(response.caracteristicas.minutos + ' min');
                    $('#resSMS').text(response.caracteristicas.sms + ' SMS');
                    $('#resTipo').text(response.caracteristicas.tipo_servicio === 'prepago' ? 'Prepago' : 'Pospago');
                    $('#planNombre').text(response.plan_recomendado);
                    
                    // Mostrar promoción si existe
                    if (response.promocion) {
                        $('.alert-warning').html('<i class="bi bi-gift-fill me-2"></i><strong>Promoción especial:</strong> ' + response.promocion);
                    }
                } else {
                    alert('Error al calcular la cotización: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión con el servidor');
                // Mostrar cálculo local como fallback
                mostrarCalculoLocal();
            }
        });
    }
    
    function mostrarCalculoLocal() {
        // Cálculo local como fallback (similar al anterior)
        let precioBase = 0;
        
        if (selectedService === 'prepago') {
            precioBase = (datos * 1) + (minutos * 0.01) + (sms * 0.005);
        } else {
            precioBase = 5 + (datos * 0.8) + (minutos * 0.008) + (sms * 0.004);
        }
        
        let precioFinal = Math.max(10, Math.min(100, Math.round(precioBase)));
        let planNombre = 'Plan Personalizado';
        
        if (datos <= 5) {
            planNombre = 'Plan Básico';
        } else if (datos <= 15) {
            planNombre = 'Plan Estándar';
        } else if (datos <= 30) {
            planNombre = 'Plan Avanzado';
        } else {
            planNombre = 'Plan Ilimitado';
        }
        
        $('#precioFinal').text('$' + precioFinal);
        $('#resDatos').text(datos + ' GB');
        $('#resMinutos').text(minutos + ' min');
        $('#resSMS').text(sms + ' SMS');
        $('#resTipo').text(selectedService === 'prepago' ? 'Prepago' : 'Pospago');
        $('#planNombre').text(planNombre);
    }
    
    function finalizarCotizacion() {
        // Solicitar datos de contacto
        email = prompt('Por favor, ingresa tu email para contactarte:');
        telefono = prompt('Por favor, ingresa tu teléfono:');
        
        if (!email) {
            alert('Es necesario un email para contactarte.');
            return;
        }
        
        // Enviar datos finales al servidor
        const datosFinales = {
            tipo_servicio: selectedService,
            datos: datos,
            minutos: minutos,
            sms: sms,
            email: email,
            telefono: telefono
        };
        
        $.ajax({
            url: 'cotizar.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosFinales),
            success: function(response) {
                if (response.success) {
                    alert('¡Gracias por tu solicitud! Un asesor se pondrá en contacto contigo para finalizar la contratación de tu plan personalizado.');
                    
                    // Cerrar el modal
                    $('#cotizadorModal').modal('hide');
                    
                    // Reiniciar el cotizador para la próxima vez
                    resetCotizador();
                } else {
                    alert('Error al guardar la cotización: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión, pero hemos recibido tu solicitud. Te contactaremos pronto.');
                $('#cotizadorModal').modal('hide');
                resetCotizador();
            }
        });
    }
    
    function suscribirNewsletter(email) {
        // En una implementación real, haríamos una llamada AJAX al servidor
        // Por ahora, simulamos el proceso
        $('#newsletterAlert').removeClass('d-none');
        $('#newsletterForm').hide();
        
        console.log('Email suscrito:', email);
        // Aquí iría la llamada AJAX para guardar en la base de datos
    }
    
    function resetCotizador() {
        currentStep = 1;
        selectedService = '';
        datos = 10;
        minutos = 1000;
        sms = 500;
        email = '';
        telefono = '';
        
        // Restablecer la interfaz
        $('.service-card').removeClass('selected');
        $('#datos').val(10);
        $('#minutos').val(1000);
        $('#sms').val(500);
        $('#datosValue').text('10 GB');
        $('#minutosValue').text('1000 min');
        $('#smsValue').text('500 SMS');
        
        // Volver al primer paso
        $('.cotizador-step').removeClass('active');
        $('#step1').addClass('active');
        updateNavigationButtons();
    }
    
    // Efectos visuales adicionales
    $('.plan-card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
});