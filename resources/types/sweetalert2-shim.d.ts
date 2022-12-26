declare module '@nathanaelytj/sweetalert2/dist/sweetalert2*' {
  export * from '@nathanaelytj/sweetalert2'
  // "export *" does not matches the default export, so do it explicitly.
  export { default } from '@nathanaelytj/sweetalert2' // eslint-disable-line
}