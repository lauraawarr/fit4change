console.log(fitness_data);

    function upperFirst(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    function drawCharts(){
    var data = process(timeseries);
    graph(data);
    graphGoal(data);
    graphDailyData(fitness_data['caloriesOut'], fitness_data['calorieGoal'], 'calories');
    graphDailyData(fitness_data['floors'], fitness_data['floorGoal'], 'floors');
    graphDailyData(fitness_data['steps'], fitness_data['stepGoal'], 'steps');
    graphDailyData(fitness_data['lightlyActive'], '1000', 'active-mins');
    };

    function process(timeSeries) {
        return timeSeries.map(
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
    }; // end process


    function graph(timeSeries) {
        
        stepData = new google.visualization.DataTable;
        stepData.addColumn('string', 'Date');
        stepData.addColumn('number', upperFirst(user_data['goalUnits']));

        stepData.addRows(timeSeries);
        
        console.log(timeSeries.map(function(x){return x[1]} ));

        var options = {
          title: upperFirst(user_data['goalUnits']) + ' this Week',
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
    }; //end graph

    function graphGoal(timeSeries) {
        var currentTotal = 0;
        timeSeries.map( function(x){return currentTotal += x[1]});
        var goal;
        if (user_data['goalTime'] == 'week'){
            goal = user_data['goalNum'];
        } else if (user_data['goalTime'] == 'day'){
            goal = 7 * user_data['goalNum'];
        };

        //displays post option is user has reached goal
        if (parseInt(currentTotal)/parseInt(goal) >= 1){
            document.querySelector('#share-post').style.display = 'flex';
            document.querySelector('#goal-percentage').style.display = 'none';
        } else {
            document.querySelector('#goal-percentage').style.display = 'flex';
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
    }; //end graphGoal

    function graphDailyData(value, goal, div) {
        var name = div.replace("-", "</br>");
        var colours = ['#D14039', '#2b605b', '#6bb2aa' , '#2b605b', '#6bb2aa'];
        var colour = colours[Math.floor(colours.length*Math.random())];
        
        //calculates percentage of goal remaining
        var toGo = (goal - value < 0) ? 0 : goal - value;
        var goalData = google.visualization.arrayToDataTable([ 
            ['Progress','Percent'],
            [upperFirst(div), value],
            [upperFirst(div) + ' To Go', toGo]
            ]);
        
        var options = {
          legend: 'none',
          tooltip: {trigger: 'none'},
          pieSliceText: 'none',
          colors: [colour, '#282826' ],
          backgroundColor: {fill: 'transparent', stroke: 'transparent'},
          pieHole: 0.7,
          pieSliceBorderColor: 'transparent',
          chartArea: {left: 0, right: 0, top: 10, bottom: 10}
        };

        //prints new progress percentage in donut chart center
        document.querySelector('#' + div + ' .overlay > h2').innerHTML = value + "</br>" + name;

        var pie_chart = new google.visualization.PieChart(document.querySelector('#' + div + ' > .chart'));

        pie_chart.draw(goalData, options);
    }; //end graphGoal


    var settings = document.querySelector('#settings');
    var settingsBar = document.querySelector('#settings-bar');
    var nav = document.querySelector('#nav');
    var navBar = document.querySelector('#nav-bar');

    settings.addEventListener('click', function(){
        settingsBar.classList.add('settings-visible');
        navBar.classList.add('settings-visible');
    }); //end EventListener

    var closeSettings = document.querySelector('#close-settings');
    closeSettings.addEventListener('click', function(){
        settingsBar.classList.remove('settings-visible');
        navBar.classList.remove('settings-visible');
    }); //end EventListener

    //revokes access token on user logout
    var logoutLink = document.querySelector('#logout');
    var code = window.btoa('< clientID >: < Bae64 encoded clientSecret> '); //Bae64 encode string

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
        var fitbitAccessToken = null;
        }); //end on logout click 