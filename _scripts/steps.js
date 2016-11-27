console.log(user_data);
window.addEventListener('load', function(){

function upperFirst(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
};

var fitbitAccessToken;

    if (!window.location.hash) {
        window.location.replace('index.html');
    } else {
        var fragmentQueryParameters = {};
        window.location.hash.slice(1).replace(
            new RegExp("([^?=&]+)(=([^&]*))?", "g"),
            function($0, $1, $2, $3) { fragmentQueryParameters[$1] = $3; }
        );

        fitbitAccessToken = fragmentQueryParameters.access_token;
        //console.log(fitbitAccessToken);
    }

    // Make an API request and graph it
    var processResponse = function(res) {
        if (!res.ok) {
            throw new Error('Fitbit API request failed: ' + res);
        }
     
        var contentType = res.headers.get('content-type')
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return res.json();
        } else {
            throw new Error('JSON expected but received ' + contentType);
        }
    }

    var process = function(timeSeries) {
        var str = 'activities-' + user_data['goal'];
        return timeSeries[str].map(
            function(measurement){
                //divides date string into day, month, year
                var date = measurement.dateTime.split('-').map(
                            function(dateSegment) {
                                return Number.parseInt(dateSegment);
                            }) //end split map
                var year = date[0];
                var month = date[1];
                var day = date[2];

                return [month + "-" + day, Number.parseInt(measurement.value) ]; //converts step count to integer
            } //end measure func 
        ); //end map
    } // end processSteps


    var graph = function(timeSeries) {
        
        stepData = new google.visualization.DataTable;
        stepData.addColumn('string', 'Date');
        stepData.addColumn('number', upperFirst(user_data['goal']));

        stepData.addRows(timeSeries);
        
        console.log(timeSeries.map(function(x){return x[1]} ));

        var options = {
          title: upperFirst(user_data['goal']) + ' this Week',
          //curveType: 'function',
          legend: 'none',
          title: null,
          colors: ['#D14039' ],
          backgroundColor: {fill: 'transparent'},
          crosshair: {color: '#fff'},
          hAxis: {baselineColor: '#fff', textColor: '#fff'},
          vAxis: {baselineColor: '#fff', textColor: '#fff'},
          lineWidth: 7,
          chartArea: {left: 80, right: 40, top: 40, bottom: 80}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(stepData, options);

        return timeSeries;
    }

    var graphGoal = function(timeSeries) {
        var currentTotal = 0;
        timeSeries.map( function(x){return currentTotal += x[1]});
        var goal;
        if (user_data['goalTime'] == 'week'){
            goal = user_data['goalNum'];
        } else if (user_data['goalTime'] == 'day'){
            goal = 7 * user_data['goalNum'];
        };

        //displays post option is user has reached goal
        console.log(currentTotal/goal);
        if (parseInt(currentTotal)/parseInt(goal) >= 1){
            document.querySelector('#share-post').style.display = 'block';
            document.querySelector('#other-post').style.display = 'none';
        } else {
            document.querySelector('#other-post').style.display = 'block';
            document.querySelector('#share-post').style.display = 'none';
        };
        
        //calculates percentage of goal remaining
        var toGo = (goal-currentTotal < 0) ? 0 : goal-currentTotal;
        var goalData = google.visualization.arrayToDataTable([ 
            ['Progress','Percent'],
            [upperFirst(user_data['goal']) + ' Completed', currentTotal],
            [upperFirst(user_data['goal']) + ' To Go', toGo]
            ]);
        
        var options = {
          //title: 'Goal Progress',
          legend: 'none',
          pieSliceText: 'none',
          colors: ['#2B605B' ,'#6BB2AA' ],
          backgroundColor: {fill: 'transparent', stroke: 'transparent'},
          pieHole: 0.7,
          pieSliceBorderColor: 'transparent',
          chartArea: {left: 20, right: 20, top: 20, bottom: 20}
        };

        //prints new progress percentage in donut chart center
        document.querySelector('#goal-progress > .overlay >h2').innerHTML = Math.ceil(100*(goal-toGo)/goal) + '%';

        var pie_chart = new google.visualization.PieChart(document.getElementById('pie_chart'));

        pie_chart.draw(goalData, options);
    }

//had issue with external libraries loading after js file initially
//solved by adding event listener
var fitbitJSON = 'https://api.fitbit.com/1/user/-/activities/'+ user_data['goal'] +'/date/2016-08-22/1w.json';
    fetch(
        fitbitJSON,
        {
            headers: new Headers({
                'Authorization': 'Bearer ' + fitbitAccessToken
            }),
            mode: 'cors',
            method: 'GET'
        }
    ).then(processResponse)
    .then(process)
    .then(graph)
    .then(graphGoal)
    .catch(function(error) {
        console.log(error);
    });
});//end onload

// var post = document.querySelector('#post-status');
// var code = window.btoa('U2uUKf13tkXvyfhL1VXSew8ee: 2q80sqSFP2ldWD9vqataZLihmR7oiwpvxrwvr51IFM6Z7bFDXm')
// post.addEventListener('click', function(){
//     fetch(
//         'https://api.twitter.com/1.1/statuses/update.json?status=Testing',
//         {
//             headers: new Headers({
//                 'Authorization': 'Bearer' + code,
//                 'Access-Control-Allow-Origin': 'http://localhost:8888', 
//                 'Access-Control-Allow-Credentials': true,
//                 //'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8.'
//             }),
//             mode: 'no-cors',
//             method: 'POST'
//         })
// });

//revokes access token on user logout
var logoutLink = document.querySelector('#logout');
var code = window.btoa('227WP9:0f947f3eed699edb1fa68a2c6d45a036'); //Bae64 encode string

logoutLink.addEventListener('click', function(){
    fetch(
        'https://api.fitbit.com/oauth/revoke',
        {
            headers: new Headers({
                'Authorization': 'Basic ' + code
            }),
            mode: 'cors',
            method: 'POST'
        });
    console.log('test')
    var fitbitAccessToken = null;
    });