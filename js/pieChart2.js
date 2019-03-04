const bCtx = $('#pieChart2')
function showPie2($category1, $category2, $category3, $category4, $category5, $category6) {
    let data = []
        data = [$category1, $category2, $category3, $category4, $category5, $category6]
        // $eat = []
        // $traffic = []
        // $life = []
        // $play = []
        // $clothe = []
        // $hoby = []

        // $eat = $category1
        // $traffic = $category2
        // $life = $category3
        // $play = $category4
        // $clothe = $category5
        // $hoby = $category6

    console.log($category1, $category2, $category3, $category4, $category5, $category6);
    new Chart(bCtx,{
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


