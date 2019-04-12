window.onload = function() {
    resetCharts();
}

function resetCharts() {
    var recentMatches = document.getElementById("recentMatches");
    recentMatches.style.display = "none";
    
    var winrateGraph = document.getElementById("winrateGraph");
    winrateGraph.style.display = "none";
    
    var heroStats = document.getElementById("heroStats");
    heroStats.style.display = "none";
}

function addRecentMatchesData(string) {
    var recentMatchesData = document.getElementById("recentMatchesData");
    recentMatchesData.innerHTML = string;
}

function addHeroStatsData(string) {
    var heroStatsData = document.getElementById("heroStatsData");
    heroStatsData.innerHTML = string;
}

function showChart() {
    var vizCard = document.getElementById("vizCard");
    vizCard.style.display = "block";
}

function showRecentMatches() {
    resetCharts();
    
    $.ajax({
        url: "http://aherrin.create.stedwards.edu/DotaHelper/Pages/PHP/getRecentMatches.php",
        method: "POST",
        success: function(data) {
            var tableBody = data;
            //console.log("SUCCESS: ");
            //console.log(tableBody);
            addRecentMatchesData(tableBody.responseText);
        },
        error: function(data) {
            var tableBody = data;
            //console.log("ERROR: ");
            //console.log(tableBody.responseText);
            addRecentMatchesData(tableBody.responseText);
        }
    });
    
    var recentMatches = document.getElementById("recentMatches");
    recentMatches.style.display = "block";
}

function showWinrateGraph() {
    resetCharts();
    
    $.ajax({
        url: "http://aherrin.create.stedwards.edu/DotaHelper/Pages/PHP/getWinrateOverTime.php",
        method: "GET",
            success: function(data) {
            //console.log(data);
            var winrates = [];
            var lossrates = [];
            var dates = [];
            var games = [];
            totGames = 0;
            monGames = 0;
            wins = 0;
            losses = 0;
            MYstring = "";
            
            for(var i in data) {
                if (MYstring == "") {
                    MYstring = data[i].MYstring;
                }
                if (MYstring != data[i].MYstring) {
                    dates.push(MYstring);
                    games.push(totGames + " Games");
                    winrates.push(Math.round((wins/monGames)*100)/100);
                    lossrates.push(Math.round((losses/monGames)*100)/100);
                    
                    MYstring = data[i].MYstring;
                    monGames = 0;
                    wins = 0;
                    losses = 0;
                }
                
                if ((data[i].isRadiant == 1 && data[i].radiant_win == 1) || (data[i].isRadiant == 0 && data[i].radiant_win == 0)) {
                    wins += 1;
                } else {
                    losses += 1;
                }
                monGames += 1;
                totGames += 1;
            }
            
            var chartdata = {
                labels: dates,
                datasets : [{
                    label: "Winrate",
                    labels: games,
                    backgroundColor: 'rgba(49, 196, 49, 0.50)',
                    borderColor: 'rgba(200, 200, 200, 0.75)',
                    hoverBackgroundColor: 'rgba(49, 196, 49, 1)',
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: winrates
                },
                {
                    label: "Lossrate",
                    labels: games,
                    backgroundColor: 'rgba(198, 0, 0, 0.50)',
                    borderColor: 'rgba(200, 200, 200, 0.75)',
                    hoverBackgroundColor: 'rgba(198, 0, 0, 1)',
                    hoverBorderColor: 'rgba(200, 200, 200, 1)',
                    data: lossrates
                }]
            };
            
            var ctx = $("#winrateCanvas");

            var barGraph = new Chart(ctx, {
                type: 'line',
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
                            stacked: false
                        }]
                    }
                }
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
    
    var winrateGraph = document.getElementById("winrateGraph");
    winrateGraph.style.display = "block";
}

function showHeroStats() {
    resetCharts();
    
    $.ajax({
        url: "http://aherrin.create.stedwards.edu/DotaHelper/Pages/PHP/getHeroStats.php",
        method: "POST",
        success: function(data) {
            var tableBody = data;
            //console.log("SUCCESS: ");
            //console.log(tableBody);
            addHeroStatsData(tableBody.responseText);
        },
        error: function(data) {
            var tableBody = data;
            //console.log("ERROR: ");
            //console.log(tableBody.responseText);
            addHeroStatsData(tableBody.responseText);
        }
    });
    
    var heroStats = document.getElementById("heroStats");
    heroStats.style.display = "block";
}