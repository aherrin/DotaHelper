function showWinrateGraph() {
    $.ajax({
        url: "http://aherrin.create.stedwards.edu/DotaHelper/Pages/PHP/getWinrateOverTime.php",
        method: "GET",
            success: function(data) {
            //console.log(data);
            var winrates = [];
            var lossrates = [];
            var games = [];
            numGames = 0;
            wins = 0;
            losses = 0;
            
            for(var i in data) {
                numGames += 1;
                games.push(numGames + " Games");
                
                if ((data[i].isRadiant == 1 && data[i].radiant_win == 1) || (data[i].isRadiant == 0 && data[i].radiant_win == 0)) {
                    wins += 1;
                } else {
                    losses -= 1;
                }
                
                winrates.push(Math.round((wins/numGames)*100)/100);
                lossrates.push(Math.round((losses/numGames)*100)/100);
            }
            
            var chartdata = {
                labels: games,
                datasets : [{
                    label: "Winrate",
                    labels: games,
                    backgroundColor: 'rgba(49, 196, 49, 0.75)',
                    borderColor: 'rgba(200, 200, 200, 0.75)',
                    hoverBackgroundColor: 'rgba(49, 196, 49, 1)',
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: winrates
                },
                {
                    label: "Lossrate",
                    labels: games,
                    backgroundColor: 'rgba(198, 0, 0, 0.75)',
                    borderColor: 'rgba(200, 200, 200, 0.75)',
                    hoverBackgroundColor: 'rgba(198, 0, 0, 1)',
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: lossrates
                }]
            };
            
            var ctx = $("#mycanvas");

            var barGraph = new Chart(ctx, {
                type: 'bar',
                data: chartdata,
                options: {
                    //responsive: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                callback: function(value, index, values) {
                                    return (value*100 + "%");
                                },
                                beginAtZero:true
                            }
                        }],
                        xAxes: [{
                            stacked: true
                        }]
                    }
                }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
};