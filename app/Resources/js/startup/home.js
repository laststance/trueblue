import React from 'react'
import { Provider } from 'react-redux'
import App from '../containers/home'
import ReactOnRails from 'react-on-rails'

const mainNode = () => {
    const store = ReactOnRails.getStore('homeStore')

    const reactComponent = (
        <Provider store={store}>
            <App/>
        </Provider>
    )
    return reactComponent
}

export default mainNode
