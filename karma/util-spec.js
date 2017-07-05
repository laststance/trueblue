import assert from 'assert'
import { getKaomoji } from '../app/Resources/js/utils/util'

describe('karma util', () => {
    it('getKaomoji', () => {
        assert(typeof getKaomoji() == 'string')
    })
})
