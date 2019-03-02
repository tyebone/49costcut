const cCtx = $('#barChart')
new Chart(cCtx, {
    type:'line',
	data: {
      labels: ['貧乏神','ひもじい', '浪費家', 'ノーマル', 'ちょっとしたすごいやつ', '毎月貯金', '金持ち金持ちコース'],
      datasets: [{
	 	label: '最高気温(度）',
	    data: [35, 34, 37, 35, 34, 35, 34],
	    borderColor: "rgba(255,0,0,1)",
	    backgroundColor: "rgba(0,0,0,0,0,0)"
	    },
	  ],
},
	options: {
		scales: {
			yAxes: [{
				ticks: {

					//グラフの最小値を0にする
					beginAtZero: true
				}
			}]
		}
	}
})