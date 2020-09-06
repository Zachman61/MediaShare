import {initialUserState} from "./User";

export const userReducer = (state, action) => {
    console.log(action)
    switch(action.type) {
        case 'LOGIN':
            return {...state, ...action.payload}
        case 'LOGOUT':
            return initialUserState
    }
}

export default userReducer
