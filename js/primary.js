$(document).ready(function(){
    $('.autosuggest').on('input', function(){
      
      // $(this).hide();
        console.log($(this));
        var search_term = $(this).val(); 
        
        $.post('ajax/search.php', {search_term: search_term}, function(data){
            $('.result').append(data);
            console.log(data);
           
            $('.result li').click(function(){
                   
                    var result_value = $(this).text().trim();

                    $('.autosuggest').val(result_value); 
                    $('.result').html('');
                    if (result_value !== '') {
                        window.location.href = 'profile.php?username=' + encodeURIComponent(result_value);
                }
            });

        });



    });
});
