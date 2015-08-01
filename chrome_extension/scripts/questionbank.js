var QuestionBank = (function() {
    /*
        {
          "_id": {
            "$id": "55b34a917b738657e17dc5f2"
          },
          "id": "tpe-spot-q01-111",
          "cat": "travel",
          "q": "位於臺北市 士林區至善路三段370巷29號 的 七星山系-坪頂古圳親山步道 該如何前往？",
          "a": "1.公車:搭小18公車到「坪頂古圳步道口」站，下車後往山上方向，走到至善路三段370巷29號。2.公車小19、303至「平等里站」下車。",
          "ts_c": 1437816373,
          "ts_v": 0,
          "img": null,
          "location": {
            "lat": "25.134289",
            "lon": "121.586731"
          },
          "url": "http://www.tcge.taipei.gov.tw/ct.asp?xItem=37196778&ctNode=67799&mp=106051"
        },
    */
    var currentIndex = 0;
    var questionAmount = 0;
    var questionSet = [];
    var answerSet = [];
    var idSet = [];
    var urlSet = [];
    var catSet = [];
    var alreadyClickedQuestions = [];

    var loadOpenData = $.Deferred(function(deferred) {
        var cat = localStorage["PIXNET_HACK_ADS_TYPE"];
        console.log("currect cat = " + 'http://test.wjhuang.net/api.php?a=getkg&n=10&cat=' + cat);
        //console.log("get current cat = " + cat);

        $.ajax({
            url: 'http://test.wjhuang.net/api.php?a=getkg&n=10&&manual=1&cat=' + cat, 
            dataType: 'json',
            success: function(result){
                questionAmount = result.data.length;

                for (var i=0;i < result.data.length;i++) {
                    questionSet.push(result.data[i].q);
                    answerSet.push(result.data[i].a);
                    idSet.push(result.data[i].id);
                    urlSet.push(result.data[i].url);
                    catSet.push(result.data[i].cat);
                }
                deferred.resolve();
            }
        });
    });

    function getNextQuestionSet() {

        if (currentIndex >= questionAmount) {
            currentIndex = 0;
        }

        var result = {};

        result.question = questionSet[currentIndex];
        result.answer = answerSet[currentIndex];
        result.id = idSet[currentIndex];
        result.url = urlSet[currentIndex];
        result.cat = catSet[currentIndex];

        ++currentIndex;

        return result;
    }

    function loadData(callback) {
        $.when(loadOpenData).then(callback);
    }

    function isAlreadyClicked(id) {
        return alreadyClickedQuestions.indexOf(id) > -1;
    }

    function setAlreadyClicked(id) {
        if (isAlreadyClicked(id)) {
            return;
        }

        alreadyClickedQuestions.push(id);
    }

    return {
        loadData: loadData,
        getNextQuestionSet: getNextQuestionSet,
        isAlreadyClicked: isAlreadyClicked,
        setAlreadyClicked: setAlreadyClicked
    }
})();