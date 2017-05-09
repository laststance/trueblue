import { createStore, applyMiddleware, compose } from 'redux'
import thunkMiddleware from 'redux-thunk'
import reducer from '../reducers/home'
import { initialStates } from '../reducers/home'
import {getObjectKeyIndex} from '../utils/util'
import {getYmdStr} from '../utils/util'

export default function configureStore(props) {

    const {
        timelineDateList,
        timelineJson,
        username,
        isLogin,
        isShowImportModal,
        isInitialImportDebug,
        transText
    } = props
    const { homeState } = initialStates

    const initialState = {
        homeState: {
            ...homeState,
            timelineDateList,
            timelineJson,
            username,
            isLogin,
            isShowImportModal,
            isInitialImportDebug,
            transText,
            currentIndex: getObjectKeyIndex(timelineJson, getYmdStr(new Date()))
        }
    }

    let composeEnhancers = typeof(window) !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
    
    const store = createStore(reducer,
        initialState,
        composeEnhancers(
            applyMiddleware(thunkMiddleware)
        )
    )
    return store
}
