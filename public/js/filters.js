var agentCountryIds = [];

$(document).ready(function () {
    $('#specialty').change(function () {
        agentCountryIds = [];
        const specialtySelected = $('#specialty').val();

        $.ajax({
            type: 'POST',
            url: '/missions/post',
            data: { specialtyId: specialtySelected },
            success: function (data) {
                $('#agent').html(data);
            }
        });
    });

    $('#country').change(function () {
        const countrySelected = $('#country').val();

        $.ajax({
            type: 'POST',
            url: '/missions/post',
            data: { countryId: countrySelected },
            success: function (data) {
                $('#local').html(data);
            }
        });
    });

    var agentSelected = [];

    $('#agent').on('change', 'input.agents', function () {

        var agentId = $(this).attr('id');

        if ($(this).is(':checked')) {
            agentSelected.push({ id: agentId });
        } else {
            agentSelected = agentSelected.filter(agent => agent.id !== agentId);
        }

        $.ajax({
            type: 'POST',
            url: '/missions/post',
            data: { agentId: agentSelected },
            success: function (data) {
                $('#target').html(data);
            }
        });
        console.log(agentSelected);
    });

});