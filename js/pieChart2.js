const bCtx = $('#pieChart2')
function showPie2(category) {
    new Chart(bCtx,{
      type: 'pie',
      data: {
        labels:['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
        datasets: [{
          data: category,
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
        title:{
          display: true,
          text: 'あなたの回答に基づく生活費の割合'
        }

      }
    })
}


