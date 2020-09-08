import React, {createContext, useReducer} from 'react'
import mediaReducer from "./mediaReducer";
export const initialMediaState = {
    media: []
}

const MediaContext = createContext({mediaState: initialMediaState, dispatchForMedia: () => null })

export const MediaProvider = ({initialState = initialMediaState, children}) => {
    const [state, dispatch] = useReducer(mediaReducer, initialState)
    return <MediaContext.Provider value={ { mediaState: state, dispatchForMedia: dispatch} }>
        {children}
    </MediaContext.Provider>
}

export default MediaContext
