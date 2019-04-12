function showBestHeroes() {
    $.ajax({
        url: "http://aherrin.create.stedwards.edu/DotaHelper/Pages/PHP/getBestHeroes.php",
        method: "GET",
            success: function(data) {
            //console.log(data);
            
            var bestHeroes = [];
            var winrate = [];
            var heroIcons = [];
            
            data.sort(function(a, b){return b.winrate - a.winrate});
            
            data = data.slice(0, 5);
            
            //console.log(data);
            //console.log(slicedData);
            
           for(var i in data) {
                winrate.push(Math.round((data[i].winrate)*100)/100);
                bestHeroes.push(data[i].hero_name);
                heroIcons.push(data[i].hero_icon);
            }
            
            
            
            var chartdata = {
                labels: bestHeroes,
                datasets : [{
                    label: "Highest Winrate Heroes",
                    labels: heroIcons,
                    backgroundColor: ['rgba(49, 196, 49, 0.75)', 'rgba(203, 26, 26, 0.75)', 'rgba(138, 26, 203, 0.75)', 'rgba(229, 229, 52, 0.75)', 'rgba(46, 56, 234, 0.75)'],
                    borderColor: 'rgba(200, 200, 200, 0.75)',
                    hoverBackgroundColor: ['rgba(49, 196, 49, 1)', 'rgba(203, 26, 26, 1)', 'rgba(138, 26, 203, 1)', 'rgba(229, 229, 52, 1)', 'rgba(46, 56, 234, 1)'],
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: winrate
                }]
            };
            
            var ctx = $("#herocanvas");

            var myDoughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: chartdata,
                options: {
                    //responsive: false,
                    rotation : (-Math.PI),
                    circumference : (Math.PI)
                }
            });
            
        
        },
        error: function(data) {
            console.log(data);
        }
    });
};