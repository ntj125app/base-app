import Swal from '@nathanaelytj/sweetalert2/dist/sweetalert2.all';

export function useResponse (response: any) {
  return Swal.fire({
  title: response.data.title,
  text: response.data.message,
  timer: 3000,
  icon: 'success',
  timerProgressBar: true,
  showConfirmButton: true,
  willClose: () => {
    if (typeof response.data.redirect !== 'undefined') {
    window.location.assign(response.data.redirect)
    }
  }
  })
}

export function useError (error: any) {
  let eobj = error.response.data.errors
  let eobs = error.response.status
  if (typeof eobj !== 'undefined') {
  let msg = '';
  for (let value in eobj) {
    msg += eobj[value] + " ";
  }
  Swal.fire({
    title: 'Error!!!',
    text: msg,
    timer: 3000,
    icon: 'error',
    timerProgressBar: true,
    showConfirmButton: true,
    willClose: () => {
    if (typeof error.response.data.redirect !== 'undefined') {
      window.location.assign(error.response.data.redirect)
    }
    }
  })
  } else if (eobs == '403') {
  Swal.fire({
    title: 'ERROR!!! Not Authorized',
    text: 'Contact admin for solution',
    timer: 3000,
    icon: 'error',
    timerProgressBar: true,
    showConfirmButton: true,
    willClose: () => {
    if (typeof error.response.data.redirect !== 'undefined') {
      window.location.assign(error.response.data.redirect)
    }
    }
  })
  } else {
  Swal.fire({
    title: 'Uncaught Errors!!!',
    text: error,
    timer: 3000,
    icon: 'error',
    timerProgressBar: true,
    showConfirmButton: true,
    willClose: () => {
    if (typeof error.response.data.redirect !== 'undefined') {
      window.location.assign(error.response.data.redirect)
    }
    }
  })
  }
}