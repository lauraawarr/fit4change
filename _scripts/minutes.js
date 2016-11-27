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

    var processMinutes = function(timeSeries) {
        return timeSeries['activities-minutesFairlyActive'].map(
            function(measurement){
                //divides date string into day, month, year
                var date = measurement.dateTime.split('-').map(
                            function(dateSegment) {
                                return Number.parseInt(dateSegment);
                            }) //end split map
                var year = date[0];
                var month = date[1];
                var day = date[2];

                return [month + "-" + day, Number.parseInt(measurement.value) ]; //converts min count to integer
            } //end measure func 
        ); //end map
    } // end processSteps


    var graphMinutes = function(timeSeries) {
        
        stepData = new google.visualization.DataTable;
        stepData.addColumn('string', 'Date');
        stepData.addColumn('number', 'Minutes');

        stepData.addRows(timeSeries);
        
        console.log(timeSeries.map(function(x){return x[1]} ));

        var options = {
          title: 'Minutes of Fair Activity This Week',
          //curveType: 'function',
          legend: 'none',
          colors: ['#D14039' ],
          backgroundColor: {fill: 'transparent'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(stepData, options);

        return timeSeries;
    }

    var graphGoal = function(timeSeries) {
        var currentTotal = 0;
        timeSeries.map( function(x){return currentTotal += x[1]});
        var goal;
        if (user_data['goal'] == 'week'){
            goal = user_data['goalNum'];
        } else if (user_data['goal'] == 'day'){
            goal = 7*user_data['goalNum'];
        };
        
        var goalData = google.visualization.arrayToDataTable([ 
            ['Progress','Percent'],
            ['Minutes Completed', currentTotal],
            ['Minutes To Go', goal]
            ]);
        
        var options = {
          //title: 'Goal Progress',
          legend: 'none',
          colors: ['#2B605B' ,'#6BB2AA' ],
          backgroundColor: {fill: 'transparent'}
        };

        var pie_chart = new google.visualization.PieChart(document.getElementById('pie_chart'));

        pie_chart.draw(goalData, options);
    }

//had issue with external libraries loading after js file initially
//solved by adding event listener 
window.addEventListener('load', function(){
    fetch(
        'https://api.fitbit.com/1/user/-/activities/minutesFairlyActive/date/2016-08-22/1w.json',
        {
            headers: new Headers({
                'Authorization': 'Bearer ' + fitbitAccessToken
            }),
            mode: 'cors',
            method: 'GET'
        }
    ).then(processResponse)
    .then(processMinutes)
    .then(graphMinutes)
    .then(graphGoal)
    .catch(function(error) {
        console.log(error);
    });
}); //end onload

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