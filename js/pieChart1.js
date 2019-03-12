//円グラフ

const aCtx = $('#pieChart1')
function showPie1(type) {
    let data = []
    if (type == 1) {
    data = [9000,2000,1500,11000,2300,3000]
    } else if (type == 2) {
    data = [7500,2000,1500,11000,2300,3000]
    } else if (type == 3) {
    data = [16500,3000,6000,15000,3500,3000]
    } else {
    data = [16500,3000,3000,11000,3500,5000]
    }
    new Chart(aCtx,{
      type: 'doughnut',
      data: {
        labels:['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
        datasets: [{
          data: data,
          backgroundColor:[
            'rgba(255,91,162,0.8)',
            'rgba(54,162,233,0.8)',
            'rgba(255,206,86,0.8)',
            'rgba(275,99,132,0.8)',
            ],
        }]
      },
      options:{
        title:{
          display: true,
          text: '出費割合'
        }

      }
    })
}