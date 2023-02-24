import { isNil } from 'lodash'

function JsonToFormData(data) {
  let formData = new FormData()
  Object.keys(data).forEach((key) => {
    if (isNil(data[key])) {
      return
    }
    if (data[key] instanceof File) {
      formData.append(key, data[key])
    } else if (Array.isArray(data[key])) {
      data[key].forEach((item) => {
        formData.append(key + '[]', item)
      })
    } else if (typeof data[key] == 'object') {
      formData.append(key, JSON.stringify(data[key]))
    } else {
      formData.append(key, data[key])
    }
  })

  return formData
}

function checkNull(value) {
  return (
    ['null', 'undefined'].includes(typeof value) ||
    value === null ||
    value.length == 0
  )
}
export { JsonToFormData }
export default JsonToFormData
