(function(){
  $(document).on('click', ':file', function(){
    $('label[for="'+$(this).attr('id')+'"]').html('Wybierz swoją miniaturę');
  });

  $(document).on('change', ':file', function(){
    if ($(this)[0].files[0] != undefined){
      let size = Math.round($(this)[0].files[0].size/1024), ext = 'KB';

      if (size > 1024){
        size = Math.round(size/1024);
        ext = 'MB';
      }

      $('label[for="'+$(this).attr('id')+'"]').html($(this)[0].files[0].name+'<span class="pl-3">'+size+ext+"</span>");
    };
  });
})();
