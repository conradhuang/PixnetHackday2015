/*
    name: contentscript.js
*/

(function() {

$(document).ready(function() {

	chrome.storage.onChanged.addListener(function(changes, namespace) {
      var storage = changes["PIXNET_HACK_ADS_TYPE"];
      localStorage["PIXNET_HACK_ADS_TYPE"] = storage.newValue;
      console.log("contentscript.js cat changed = " + localStorage["PIXNET_HACK_ADS_TYPE"]);
  	});

	// adblocker rules
	var target_dom = [
		// div
		"www.books.com.tw##.bg_ad",
		"www.books.com.tw##.header_box6",
		"www.books.com.tw##.flash_banner",
		"www.mobile01.com##.catbar-ad",
		"www.mobile01.com##.ad-c",
		"www.mobile01.com##.ad-a2",
		"www.mobile01.com##.ad2_55642",
		"www.mobile01.com##.ad-a1",
		"www.mobile01.com###ad_56017",
		"www.bing.com##.b_ad",
		"www.appledaily.com.tw###google_flash_inline_div",
		"www.appledaily.com.tw###google_image_div",
		"www.appledaily.com.tw###leaderboard_ad_container",
		"www.appledaily.com.tw###google_image_div",
		"www.appledaily.com.tw###rectangleAD1_ad_container",
		"www.gamer.com.tw##.BA-billboard",
		"www.bnext.com.tw###google_ads_frame1",
		"www.mobile01.com###ad_55707",
		"udn.com###aleosoftflash",
		"udn.com###google_flash_div",
		"udn.com###ad_1",
		"udn.com###ad_2",
		"udn.com###ad_content",
		"udn.com###ad_mag",

		// iframe
		"www.appledaily.com.tw###crowdynews-iframe",
		// img
		"gamer.com.tw**MATERIALS",
		"gamer.com.tw**8kele",
		"www.appledaily.com.tw**googlesyndication"
	];

	function showPixnet() {
		var pixnet = document.createElement("img");
		$(pixnet).addClass("pixnet-logo");
		pixnet.src = chrome.extension.getURL('icons/icon-24.png');
		$("body").append(pixnet);
	}

	function setFlashParamWmode() {
		$("object").append(
		    $("<param/>").attr({
		        'name': 'wmode',
		        'value': 'transparent'
		    })
		).find("embed").attr('wmode', 'transparent')
	}

	function googleTranslation(text, lang) {
		var audio = new Audio();
		audio.src ='http://translate.google.com/translate_tts?ie=UTF-8&q=' + text + '&tl=' + lang;
		audio.play();
	}

	function adsHandling(target) {
		var card = document.createElement("div");
		$(card).addClass("card");
		$(card).attr('id', 'card');
		$(card).css({
	    	"width": $(target).width(),
	    	"height": $(target).height()
	    });

		var ads_container = document.createElement("div");
		var ads_text = document.createElement("div");

		var questionSet = QuestionBank.getNextQuestionSet();

		$(target).css("display", "none");
		$(ads_container).addClass("open-ads front");
		$(ads_text).addClass("open-ads-text");

    	$(ads_container).css({
	    	"width": $(target).width(),
	    	"height": $(target).height()
	    });

    	// create back
    	var answer_container = document.createElement("div");
    	$(answer_container).addClass("open-ads back");
    	$(answer_container).css({
	    	"width": $(target).width(),
	    	"height": $(target).height()
	    });

	    var answer_text = document.createElement("div");
	    $(answer_text).addClass("open-ads-text");
	    $(answer_text).text(questionSet.answer);

	    $(answer_container).append($(answer_text));

	    var curr_question = questionSet.question;
	    if (!questionSet.question || questionSet.question.length === 0) {
	    	curr_question = "No Available Data";
	    }

    	$(ads_text).typed({
	        strings: [curr_question],
	        typeSpeed: 10,
            startDelay: 600,
            loop: false,
            showCursor: true,
            cursorChar: "|"
    	});

    	$(ads_container).append(ads_text);
    	$(card).append($(ads_container));
    	$(card).append($(answer_container));

    	$(card).insertAfter($(target));
    	$(card).flip();
    	$(card).click(function(event){

			event.preventDefault();

    		// don't increase the count if the users clicking answers
    		if ($(event.target).attr('class').indexOf('back') > -1) {
    			console.log("don't increase when users are in the back side");
    			return;
    		}

    		// don't increase the count if users already clicked it
		    if (QuestionBank.isAlreadyClicked(questionSet.id)) {
		    	console.log("already clicked");
		    	return;
		    }

		    QuestionBank.setAlreadyClicked(questionSet.id);

		    // id ++
		    $.ajax({
	            url: 'http://test.wjhuang.net/api.php?a=logkg&id=' + questionSet.id,
	            dataType: 'json',
	            success: function(result){
	                console.log("id = " + questionSet.id + " ++");
	            }
	        });
		});
	}

	QuestionBank.loadData(function() {
		setFlashParamWmode();

	    for (i = 0; i < target_dom.length; i++) {
	    	// div
	    	var sep = "##";
	    	// img
	    	if (target_dom[i].indexOf("**") != '-1') {
	    		sep = "**";
	    	}

	    	var target_set = target_dom[i].split(sep);
		    var target_domain = target_set[0];
		    var target_element = target_set[1];

	    	if (window.location.href.indexOf(target_domain) != -1) {
	    		if (sep == "**") {
	    			$('img[src*="' + target_element + '"]').each( function( index, element ){
						adsHandling(this);
					});
				} else if (sep == "##") {
					adsHandling(target_element);
		    	}
	    	}
		}

		$(".open-ads").click(function() {
			//googleTranslation("南港站最多可以停三輛腳踏車", 'zh-CN');
		});

		$('.open-ads').flowtype({
		   minimum   : 500,
		   maximum   : 1800,
		   minFont   : 6,
		   maxFont   : 60,
		   fontRatio : 24
		});
	});


});

})();



