/**
 * Created by macbookpro on 23.02.15.
 */
(function ($) {
  var thumbs = {}, prev, next, transform, transition, i, row_index;
    thumbs.container = $('.thumbs .thumbs-container');
    thumbs.rows = $('.thumbs-container > ul');
    thumbs.rowcount = thumbs.rows.length;
    thumbs.items = $('.thumbs-container .thumb');
    thumbs.itemscount = thumbs.items.length;
    thumbs.test = 'hello';

  transition = 'transform .5s ease-in-out';

  window.thumbs = thumbs;

  for (i=0; i < thumbs.rowcount; i++) {
    remove_animClasses(thumbs.container);
    if ($(thumbs.rows[i]).hasClass('row-selected')){
      thumbs.container.addClass('x-'+(i*80));
      row_index = i;
      break;
    }

  }

  function remove_animClasses($item){
    for(var l=0; l<thumbs.rowcount; l++){
      $item.removeClass('x-'+(l*80));
    }
  };

  prev = $('.thumbs-prev');
  next = $('.thumbs-next');

  $(prev).click(function(e){

    //transform = 'translate3d(-80px,0px,0px)';
    //render(transform, transition,thumbs.container[0]);
    //thumbs.container.animate({left:'-80px'},400 ,'linear');
    //thumbs.container.removeClass('x-0 x-80 x160').addClass('x-80');

    row_index--;

    remove_animClasses(thumbs.container);
    thumbs.container.addClass('x-'+( 80 * ( row_index )));

    if (row_index == 0){
      // we are on first row
      prev.removeClass('disabled enabled').addClass('disabled');
    }
    if (row_index < (thumbs.rowcount-1)) {
      // there are more rows
      next.removeClass('disabled enabled').addClass('enabled');
    } else {
      next.removeClass('disabled enabled').addClass('disabled');
    }

    e.preventDefault();

  });
  $(next).click(function(e){

    row_index++;
    remove_animClasses(thumbs.container);
    thumbs.container.addClass('x-'+( 80 * ( row_index )));

    prev.removeClass('disabled enabled').addClass('enabled');

    if (row_index == (thumbs.rowcount -1)){
      // there are no more rows
      next.removeClass('disabled enabled').addClass('disabled');

    }else{
      next.removeClass('disabled enabled').addClass('enabled');
    }
    e.preventDefault();
  });


  function render(transform,transition,elem){

    elem.style.webkitTransform = transform;
    elem.style.mozTransform = transform;
    elem.style.msTransform = transform;
    elem.style.transform = transform;
    elem.style.webkitTransition = transition;
    elem.style.mozTransition = transition;
    elem.style.msTransition = transition;
    elem.style.transition = transition;
  }





})(jQuery);