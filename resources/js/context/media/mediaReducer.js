
export const mediaReducer = (state, action) => {
    console.log(action)
    switch(action.type) {
        case 'LIST':
            return {...state, media: action.payload}
        case 'ADD':
            const addMedia = [...state.media]
            addMedia.unshift(action.payload)
            return {...state, media: addMedia}
        case 'REMOVE':
            return {...state, media: state.media.filter( (elm) => elm.id !== action.payload)}
    }
}

export default mediaReducer
