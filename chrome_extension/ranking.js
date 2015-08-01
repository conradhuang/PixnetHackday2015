$(document).ready(function() {
  var _gCount = 0;
  var _gData = null;

  var appendItem = function(container) {
    var html = '';
    // for (; count < _count + _gDataCount; count++) {
    //   var questionSet = QuestionBank.getNextQuestionSet();
    //   if (count === 0) {
    //     html += '<div class="item"><div class="question_part">' +
    //       '<div class="rank-1"></div><p>' + questionSet.question + '</p>' +
    //       '</div><div class="answer_part"><p>答案 : ' + questionSet.answer + '</p></div></div>';
    //   } else {
    //     html += '<div class="item"><div class="question_part">' +
    //       '<div class="rank">' + (count + 1) + '</div><p>' + questionSet.question + '</p>' +
    //       '</div><div class="answer_part"><p>答案 : ' + questionSet.answer + '</p></div></div>';
    //   }
    // }

    var limit = _gCount + 8;
    var local = _gCount;
    for (_gCount; _gCount < limit; ++_gCount) {
      var e = _gData.data[_gCount];
      if (!e)
        break;
      if (_gCount === 0) {
        html += '<div class="item"><div class="question_part">' +
          '<div class="rank-1"></div><p>' + e.qa.q + '</p>' +
          '</div><div class="answer_part"><p>答案 : ' + e.qa.a + '</p>' +
          '<div class="share_block">' +
          '<a href="https://www.facebook.com/sharer/sharer.php?u=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/fb-like.png"></a>' +
          '<a href="https://twitter.com/home?status=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/twitter-share.png"></a>' +
          '<a href="https://plus.google.com/share?url=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/gplus-share.png"></a>' +
          '</div>' +
          '</div></div>';
      } else {
        html += '<div class="item"><div class="question_part">' +
          '<div class="rank">' + (_gCount + 1) + '</div><p>' + e.qa.q + '</p>' +
          '</div><div class="answer_part"><p>答案 : ' + e.qa.a + '</p>' +
          '<div class="share_block">' +
          '<a href="https://www.facebook.com/sharer/sharer.php?u=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/fb-like.png"></a>' +
          '<a href="https://twitter.com/home?status=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/twitter-share.png"></a>' +
          '<a href="https://plus.google.com/share?url=http://test.wjhuang.net/a/' + e.qa.id + '" target="_blank"><img src="images/gplus-share.png"></a>' +
          '</div>' +
          '</div></div>';
      }
    }


    console.log('reload')
    $(container).masonry({
      itemSelector: '.item',
      columnWidth: 320,
      animationOptions: {
        duration: 400
      }
    });
    console.log('conti')
    var $items = $(html);
    $(container).append($items).masonry('appended', $items);

    $('div.item').on('click', function() {
      $(this).children()[1].style['display'] = "block";
      $(container).masonry('layout');
    });
  }


  //IE9, Chrome, Safari, Opera
  var bindMouse = function(container, data) {
    $(container).bind('mousewheel', function(e) {
      if (e.originalEvent.wheelDelta < 0) {
        //scroll down
        // console.log('Down');
        if ($(window).scrollTop() + $(window).height() == $(document).height()) {
          console.log("到底了");
          if (_gCount < _gData.data.length)
            appendItem(container);
        }
      } else {
        //scroll up
      }

      //prevent page fom scrolling
      //return false;
    });
  }

  var loadData = function(cat) {
    _gCount = 0;
    var id = '#' + cat;
    // $(this).tab('show');
    $(id).empty();
    $.ajax({
      url: "http://test.wjhuang.net/api.php?a=stat&cat=" + cat,
      dataType: "json"
    }).done(function(data) {
      _gData = data;
      appendItem(id);
      bindMouse(id);
    });
  }

  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      $('#' + $(e.target).attr("aria-controls")).masonry();
    })
    // QuestionBank.loadData(appendItem.bind(this, _gCount));
  loadData('travel');

  // 觀光 travel
  $('#li-travel a').click(function(e) {
    e.preventDefault();

    loadData('travel')
  });


  // 自然 natural
  $('#li-natural a').click(function(e) {
    e.preventDefault();

    loadData('natural')
  });

  // 生活 lifestyle
  $('#li-lifestyle a').click(function(e) {
    e.preventDefault();
    loadData('lifestyle')
  });

  // 醫療 care
  $('#li-care a').click(function(e) {
    e.preventDefault();
    loadData('care')
  });

  // 理財 finance
  $('#li-finance a').click(function(e) {
    e.preventDefault();
    loadData('finance')
  });

  // 交通 trans
  $('#li-trans a').click(function(e) {
    e.preventDefault();
    loadData('trans')
  });

  // 政治 politic
  $('#li-politic a').click(function(e) {
    e.preventDefault();
    loadData('politic')
  });

  // 其他 other
  $('#li-other a').click(function(e) {
    e.preventDefault();
    loadData('other')
  });
});
