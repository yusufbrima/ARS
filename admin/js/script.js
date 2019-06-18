
/*Natural Language Search for user names*/
  $('#inputBlockUsername').keyup(function(){
        //$('#feedback').append('a');
        $.get('include/username_block_search.php', {inputBlockUsername: $('#inputBlockUsername').val()},
            function(result){
                $('#name_result').html(result).show();
                $('#all_names').css('visibility','hidden');
            });
  });
