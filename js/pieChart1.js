//円グラフ
Chart.defaults.global.defaultFontColor = 'black';
const aCtx = $('#pieChart1')
function showPie1(type) {
    let data = []
    if (type == 1) {
    data = [31,7,5,38,8,10]
    } else if (type == 2) {
    data = [27,7,5,40,8,11]
    } else if (type == 3) {
    data = [35,6,13,32,7,6]
    } else {
    data = [39,7,7,26,8,12]
    }

    new Chart(aCtx,{
      type: 'pie',
      data: {
        labels:['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
        datasets: [{
          data: data,
          backgroundColor:[
            '#ff3300',
            'RGB(255,102,0)',
            '#f76b1c',
            '#f7872d',
            '#f89939',
            '#f8a13e',
            ],
        }]
      },
      options:{
        tooltips: {
                callbacks: {
                    label: function (tooltipItem, data){
                        return data.labels[tooltipItem.index]
                        + ": "
                        + data.datasets[0].data[tooltipItem.index]
                        + " % "
                    }
                }
            },
        title:{
          display: true,
          text: 'あなたと同じ性別、年代の平均'
        }

      }
    })
}