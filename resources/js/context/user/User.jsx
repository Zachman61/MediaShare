import React, {createContext, useReducer} from 'react'
import userReducer from "./userReducer";
export const initialUserState = {
    discord_id: 0,
    username: '',
    avatar: '',
    id: 0,
    is_admin: 0
}

const UserContext = createContext({userState: initialUserState, dispatchForUser: () => null })

export const UserProvider = ({initialState = initialUserState, children}) => {
    const [state, dispatch] = useReducer(userReducer, initialState)
    return <UserContext.Provider value={ { userState: state, dispatchForUser: dispatch} }>
        {children}
    </UserContext.Provider>
}

export default UserContext
