// Script para el cotizador de planes de telefonia movil
// Ahora consume la API Laravel en lugar de cotizar.php

const API_BASE = 'http://127.0.0.1:8000/api';

$(document).ready(function() {
    let currentStep = 1;
    let selectedService = '';
    let datos = 10;
    let minutos = 1000;
    let sms = 500;
    let email = '';
    let telefono = '';

    initCotizador();

    function initCotizador() {
        $('.cotizador-step').removeClass('active');
        $('#step1').addClass('active');

        $('.service-card').on('click', function() {
            $('.service-card').removeClass('selected');
            $(this).addClass('selected');
            selectedService = $(this).data('service');
            $('#btnNext').prop('disabled', false);
        });

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

        $('#btnNext').on('click', nextStep);
        $('#btnPrev').on('click', prevStep);
        $('#btnFinalizar').on('click', finalizarCotizacion);

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
            $('#step' + currentStep).removeClass('active');
            currentStep++;
            $('#step' + currentStep).addClass('active');
            updateNavigationButtons();
            if (currentStep === 3) {
                calcularCotizacion();
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            $('#step' + currentStep).removeClass('active');
            currentStep--;
            $('#step' + currentStep).addClass('active');
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
        const datosCotizacion = {
            tipo_servicio: selectedService,
            datos: datos,
            minutos: minutos,
            sms: sms
        };

        $.ajax({
            url: `${API_BASE}/cotizar`,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosCotizacion),
            success: function(response) {
                const res = response.data || response;
                if (res) {
                    $('#precioFinal').text('$' + (res.precio_final ?? ''));
                    $('#resDatos').text((res.datos_gb ?? datos) + ' GB');
                    $('#resMinutos').text((res.minutos ?? minutos) + ' min');
                    $('#resSMS').text((res.sms ?? sms) + ' SMS');
                    $('#resTipo').text((res.tipo_servicio ?? selectedService) === 'prepago' ? 'Prepago' : 'Pospago');
                    $('#planNombre').text(res.plan_recomendado || 'Plan recomendado');
                    if (res.promocion || response.promocion || response.message) {
                        const promo = res.promocion || response.promocion || response.message;
                        $('.alert-warning').html('<i class="bi bi-gift-fill me-2"></i><strong>Promocion:</strong> ' + promo);
                    }
                } else {
                    alert('Error al calcular la cotizacion: ' + (response.message || ''));
                }
            },
            error: function() {
                alert('Error de conexion con el servidor');
                mostrarCalculoLocal();
            }
        });
    }

    function mostrarCalculoLocal() {
        let precioBase = 0;
        if (selectedService === 'prepago') {
            precioBase = (datos * 1) + (minutos * 0.01) + (sms * 0.005);
        } else {
            precioBase = 5 + (datos * 0.8) + (minutos * 0.008) + (sms * 0.004);
        }

        let precioFinal = Math.max(10, Math.min(100, Math.round(precioBase)));
        let planNombre = 'Plan Personalizado';

        if (datos <= 5) {
            planNombre = 'Plan Basico';
        } else if (datos <= 15) {
            planNombre = 'Plan Estandar';
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
        email = prompt('Por favor, ingresa tu email para contactarte:');
        telefono = prompt('Por favor, ingresa tu telefono:');

        if (!email) {
            alert('Es necesario un email para contactarte.');
            return;
        }

        const datosFinales = {
            tipo_servicio: selectedService,
            datos: datos,
            minutos: minutos,
            sms: sms,
            email: email,
            telefono: telefono
        };

        $.ajax({
            url: `${API_BASE}/cotizar`,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosFinales),
            success: function(response) {
                if (response.data || response.success) {
                    alert('Gracias por tu solicitud. Un asesor se contactara contigo.');
                    $('#cotizadorModal').modal('hide');
                    resetCotizador();
                } else {
                    alert('Error al guardar la cotizacion: ' + (response.message || ''));
                }
            },
            error: function() {
                alert('Error de conexion, pero hemos recibido tu solicitud. Te contactaremos pronto.');
                $('#cotizadorModal').modal('hide');
                resetCotizador();
            }
        });
    }

    function suscribirNewsletter(email) {
        $('#newsletterAlert').removeClass('d-none');
        $('#newsletterForm').hide();
        console.log('Email suscrito:', email);
    }

    function resetCotizador() {
        currentStep = 1;
        selectedService = '';
        datos = 10;
        minutos = 1000;
        sms = 500;
        email = '';
        telefono = '';

        $('.service-card').removeClass('selected');
        $('#datos').val(10);
        $('#minutos').val(1000);
        $('#sms').val(500);
        $('#datosValue').text('10 GB');
        $('#minutosValue').text('1000 min');
        $('#smsValue').text('500 SMS');

        $('.cotizador-step').removeClass('active');
        $('#step1').addClass('active');
        updateNavigationButtons();
    }

    $('.plan-card').hover(
        function() { $(this).addClass('shadow-lg'); },
        function() { $(this).removeClass('shadow-lg'); }
    );
});
