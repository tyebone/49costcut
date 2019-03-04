//円グラフ

const aCtx = $('#pieChart1')
function showPie1(type) {
    let data = []
    if (type == 1) {
    data = [40,15,20,60,10,49]
    } else if (type == 2) {
    data = [10,25,40,30,20,19]
    } else if (type == 3) {
    data = [20,5,45,20,10,59]
    } else {
    data = [5,45,20,10,25,19]
    }
    new Chart(aCtx,{
      type: 'pie',
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