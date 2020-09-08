import {initialUserState} from "./User";

export const userReducer = (state, action) => {
    switch(action.type) {
        case 'LOGIN':
            return {...state, ...action.payload}
        default:
        case 'LOGOUT':
            return initialUserState
    }
}

export default userReducer
