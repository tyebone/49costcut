// 円グラフ
const pCtx = $('#pieChart')
function showPie(type) {
new Chart(pCtx,{
  type: 'pie',
  data: {
    labels:['29歳以下男性','29歳以下女性','30歳以上男性','30歳以上女性'],
    datasets: [{
      data: type,
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
      text: 'タイプ別利用者数'
    }

  }
})
}
