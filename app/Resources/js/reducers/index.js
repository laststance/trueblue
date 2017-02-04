import indexReducer from './indexReducer'
import { initialState as indexState } from './indexReducer'
import { combineReducers }  from 'redux'

export default combineReducers({
    indexState: indexReducer
    
})

export const initialStates = {
    indexState
}
