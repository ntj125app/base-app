function timeGreetings(): string | null {
  let cT = new Date().getHours()
  let gT = null

  if (cT >= 4 && cT <= 10 ) { gT = 'Good Morning ' } else
  if (cT >= 11 && cT <= 14 ) { gT = 'Good Afternoon ' } else
  if (cT >= 15 && cT <= 18 ) { gT = 'Good Evening ' } else
  if (cT >= 19 && cT <= 23 ) { gT = 'Good Night ' } else
  if (cT >= 0 && cT <= 3 ) { gT = 'Good Night ' }

  return gT
}

function timeView(data: string | number | Date): string | null {
  if (data === null) { return null } else {
  let date = new Date(data)
  return date.toLocaleString("en-UK")
  }
}

function syncPromise(): Promise<boolean> {
  return new Promise((resolve,reject) => {
    resolve(true)
  })
}

export { timeGreetings, timeView, syncPromise }