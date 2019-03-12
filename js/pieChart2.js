const bCtx = $('#pieChart2')
function showPie2(category) {
    new Chart(bCtx,{
      type: 'pie',
      data: {
        labels:['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
        datasets: [{
          data: category,
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
          text: 'あなたの生活の割合'
        }

      }
    })
}


