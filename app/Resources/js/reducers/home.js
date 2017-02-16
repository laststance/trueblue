import homeReducer from './homeReducer'
import { initialState as homeState } from './homeReducer'
import { combineReducers }  from 'redux'

export default combineReducers({
    homeState: homeReducer
    
})

export const initialStates = {
    homeState
}
