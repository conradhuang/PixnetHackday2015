$(document).ready(function() {
    var _gCount = 0;
    var _gDataCount = 8;

    var appendItem = function(_count) {
        var count = _count;
        var html = '';

        for (; count < _count+_gDataCount; count++) {
            var questionSet = QuestionBank.getNextQuestionSet();
            if (count === 0) {
                html += '<div class="item" id="' + count + '"><div class="question_part">' +
                '<div class="rank-1"></div><p>' + questionSet.question + '</p>' +
                '</div><div class="answer_part"><p>答案 : ' + questionSet.answer + '</p></div></div>';
            } else {
                html += '<div class="item" id="' + count + '"><div class="question_part">' +
                '<div class="rank">' + (count+1) + '</div><p>' + questionSet.question + '</p>' +
                '</div><div class="answer_part"><p>答案 : ' + questionSet.answer + '</p></div></div>';
            }
        }

        if (_count === 0) {
            $('#container').append(html);
            $('#container').masonry({
                itemSelector: '.item',
                columnWidth : 320,
                animationOptions: {
                    duration: 400
                }
            });
        } else {
            var $items = $(html);
            $('#container').append($items).masonry('appended', $items);
        }

        $('div.item').on('click', function() {
            $(this).children()[1].style['display'] = "block";
            $('#container').masonry();
        });
    }



    //IE9, Chrome, Safari, Opera
    $('#container').bind('mousewheel', function(e) {
        if(e.originalEvent.wheelDelta < 0) {
            //scroll down
            // console.log('Down');
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                console.log("到底了");
                _gCount += _gDataCount;
                appendItem(_gCount);
            }
        }else {
            //scroll up
            console.log('Up');
        }

        //prevent page fom scrolling
        //return false;
    });


    QuestionBank.loadData(appendItem.bind(this, _gCount));

    // 觀光 travel
    $('#travel a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 觀光 排行榜');
    });

    // 自然 natural
    $('#natural a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 自然 排行榜');
    });

    // 生活 lifestyle
    $('#lifestyle a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 生活 排行榜');
    });

    // 醫療 care
    $('#care a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 醫療 排行榜');
    });

    // 理財 finance
    $('#finance a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 理財 排行榜');
    });

    // 交通 trans
    $('#trans a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 交通 排行榜');
    });

    // 政治 politic
    $('#politic a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 政治 排行榜');
    });

    // 其他 other
    $('#other a').click(function (e) {
      e.preventDefault();
      // $(this).tab('show');
      console.log('load 其他 排行榜');
    });
});
